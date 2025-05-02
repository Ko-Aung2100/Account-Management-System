
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); // <--- This line is required

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'aungkaunghtetps2100@gmail.com';         // Your Gmail address
    $mail->Password   = 'tsck swcs pvjp xcgk';            // App password from step 1
    $mail->SMTPSecure = 'tls';                          // Encryption
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('no-reply@dtu.com', 'Manager');
    $mail->addAddress('waiyanmoemyint2832001@gmail.com', 'Wai Yan');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Our Platform!';
    $mail->Body    = '<h1>Welcome!</h1><p>Thanks for joining us.</p>';
    $mail->AltBody = 'Welcome! Thanks for joining us.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

/*
//Mailhog approach
try {
   
   
    $mail->isSMTP();
    $mail->Host = 'localhost'; // MailHog SMTP host
    $mail->Port = 1025;        // MailHog SMTP port
    $mail->SMTPAuth = false;   // No authentication for MailHog
    echo "aa";

    $toEmail = 'aungkaunghtetps2100@gmail.com';
    $username = 'aung';

    // Sender and recipient
    $mail->setFrom('no-reply@example.com', 'Your App');
    $mail->addAddress($toEmail);
 
    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Our Platform!';
    $mail->Body    = "<h1>Welcome, $username!</h1><p>Thank you for registering. We're excited to have you!</p>";
    $mail->AltBody = "Welcome, $username! Thank you for registering.";
   
    // Send email
    $mail->send();
  
    return true;
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    return false;
}
*/
?>
