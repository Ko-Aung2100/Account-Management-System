<?php
session_start();

include "./templates/navigation.php"; 
include "./templates/functions.php";
include "./connection/con.php";
include "./templates/authCheck.php";
require_once 'vendor/autoload.php';
use OTPHP\TOTP;

$userId = $_SESSION['user_id'] ?? 1;
$arr =["secret"];
$user = showRecordsSelective($conn,$arr,$userId);
$showQr = false; // control flag
$secret = null;
$qrUri = null;

if ($user && $user["secret"]) {
    $secret = $user["secret"];
    // Optional: if you want to allow regenerating QR code for existing secret:
    $totp = TOTP::create($secret);
    $totp->setLabel('user@example.com');
    $totp->setIssuer('MyApp');
    $qrUri = $totp->getProvisioningUri();
    $showQr = true;
} else {
    echo "Hi";
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
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow rounded-4 p-4" style="max-width: 500px; width: 100%;">
            <div class="card-body text-center">
                <h2 class="card-title mb-4">Scan this QR code with Google Authenticator:</h2>
                <div id="qrcode" class="mb-4 d-flex justify-content-center"></div>
                <p class="card-text text-center text-break">
                    Or manually enter this secret:
                    <strong class="d-block mt-2"><?= htmlspecialchars($secret) ?></strong>
                </p>
            </div>
        </div>
    </div>

<?php endif; ?>

<!-- CSS and JS for QR code -->
<style>
    #qrcode {
        width: 200px;
        height: 200px;
        margin: auto;
        
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
                height: 200,
            });

        }
    });
</script>
<?php endif; ?>

<?php include "./templates/footer.php"; ?>
