<?php
session_start();

include "./templates/navigation.php"; 
include "./templates/functions.php";
include "./connection/con.php";
require_once 'vendor/autoload.php';
use OTPHP\TOTP;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'] ?? 1;

$stmt = $conn->prepare("SELECT secret FROM Users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$showQr = false; // control flag
$secret = null;
$qrUri = null;

if ($user && $user["secret"]) {
    echo "Your authentication Confidentials";
    $secret = $user["secret"];
    // Optional: if you want to allow regenerating QR code for existing secret:
    $totp = TOTP::create($secret);
    $totp->setLabel('user@example.com');
    $totp->setIssuer('MyApp');
    $qrUri = $totp->getProvisioningUri();
    $showQr = true;
} else {
    $totp = TOTP::create();
    $totp->setLabel('user@example.com');
    $totp->setIssuer('MyApp');
    $secret = $totp->getSecret();

    $stmt = $conn->prepare("UPDATE Users SET secret=? WHERE id=?");
    $stmt->bind_param("si", $secret, $userId);
    $stmt->execute();

    $qrUri = $totp->getProvisioningUri();
    $showQr = true;
}

?>

<?php if ($showQr): ?>
    <h2>Scan this QR code with Google Authenticator:</h2>
    <div id="qrcode"></div>
    <p>Or manually enter this secret: <strong><?= htmlspecialchars($secret) ?></strong></p>
<?php endif; ?>

<!-- CSS and JS for QR code -->
<style>
    #qrcode {
        width: 200px;
        height: 200px;
        margin: 10px 0;
    }
</style>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

<?php if ($showQr): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const qrCodeDiv = document.getElementById("qrcode");
        if (qrCodeDiv) {
            const qrUri = <?= json_encode($qrUri) ?>;
            new QRCode(qrCodeDiv, {
                text: qrUri,
                width: 200,
                height: 200
            });
        }
    });
</script>
<?php endif; ?>

<?php include "./templates/footer.php"; ?>
