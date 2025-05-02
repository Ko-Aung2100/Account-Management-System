<?php 
include "./templates/navigation.php"; 
include "./templates/functions.php"; 
include "./connection/con.php";// Your DB connection
include "./templates/errorReport.php";

session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//check email is already verify or not?
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT verified FROM Users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if already logged in
if (isset($_SESSION['user_id']) and $user["verified"] === 1) {
    header("Location: dashboard.php");
    exit;
}


$email = $_SESSION["email"];
$token = $_SESSION["token"];
$name = $_SESSION["username"];
echo $email;
$mail = new PHPMailer(true); // <--- This line is required
$confirmLink= $domain. "verifySuccessful.php?token=" . $token;
try {
   
   
    $mail->isSMTP();
    $mail->Host = 'localhost'; // MailHog SMTP host
    $mail->Port = 1025;        // MailHog SMTP port
    $mail->SMTPAuth = false;   // No authentication for MailHog
    

    $toEmail = $email;
    $username = $name;
    $link = $confirmLink;

    // Sender and recipient
    $mail->setFrom('no-reply@example.com', 'Your App');
    $mail->addAddress($toEmail);
 
    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Our Platform!';
    $mail->Body    = "<h1>Welcome, $username!</h1>
    <p>Thank you for registering. We're excited to have you!</p> 
    <p>Click here to confirm your email. <a href=$confirmLink>Click Here</a> </p>";
    $mail->AltBody = "Welcome, $username! Thank you for registering.";
   
    // Send email
    $mail->send();
  
   
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    
}
?>
<div>
    <h3>We have sent verification to <?php echo $email; ?>!</h3>
    <h4>Please confirm it.</h4>

</div>
<?php include "./templates/footer.php"; ?>