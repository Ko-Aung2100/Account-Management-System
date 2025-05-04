<?php
include "./connection/con.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
// secure input , output, to prevent XSS attack
$domain = "http://localhost:3000/";
function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
// Read selected columns from the database
function showRecordsSelective($conn, $arr, $user_id) {
    // Sanitize column names by allowing only alphanumeric and underscore
    $safeColumns = array_map(function($col) {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $col);
    }, $arr);
    $output = implode(", ", $safeColumns);
    $sql = "SELECT $output FROM Users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}
 
// Read all columns
function showRecords($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

//delete user
function deleteUser($conn, $user_id) {
    $stmt = $conn->prepare("DELETE FROM Users WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function updateUser($conn, $user_id, $data) {
    $sql = "UPDATE Users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssi", $data[0], $data[1], $user_id);
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error updating profile: " . $stmt->error;
    }
    $stmt->close();
}
 

function createUser($conn, $username, $email, $password, $token) {
    echo "create user";
    echo $username . " " . $email . " ". $password . " " . $token;

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Users (username, email, password, verify_token) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "false";
        error_log("Error preparing statement: " . $conn->error);
        echo "An error occurred during registration. Please try again later.";
        $conn->close();
        exit();
    }

    // Bind parameters
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $token);

    $success = $stmt->execute();
    $stmt->close();

    echo $success;
    return $success;
}



function validateUserInput($username, $email, $password, $conn) {
    $errors = [];
    echo "hi";
    // Validate Username
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (strlen($username) > 50) {
        $errors[] = 'Username cannot exceed 50 characters.';
    }

    // Validate Email
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    } elseif (strlen($email) > 100) {
        $errors[] = 'Email cannot exceed 100 characters.';
    }

    // Validate Password
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    // Check for validation errors
    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        $conn->close();
        exit();
    }

    // Check for Duplicate Email
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error);
        echo "An error occurred during registration. Please try again later.";
        $conn->close();
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<ul>";
        echo "<li>This email is already registered. Please use a different email address.</li>";
        echo "</ul>";
        $stmt->close();
        $conn->close();
        exit();
    }

    // Clean up
    $stmt->close();
}


function sendMailforPasswordReset($email, $confirmLink, $content) {
    $mail = new PHPMailer(true);
    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host = 'localhost'; // Use 'smtp.example.com' for real SMTP
        $mail->Port = 1025;        // Port used by MailHog
        $mail->SMTPAuth = false;

        // Email details
        $mail->setFrom('no-reply@example.com', 'Your App');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $content->Subject;  
        $mail->Body    = $content->Body;
        $mail->AltBody = $content->AltBody;

        // Send email
        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "Email could not be sent. Error: {$mail->ErrorInfo}";
        return false;
    }
} 
function showAlert($message, $type = 'error') {
    $colors = [
        'error' => '#f44336',
        'warning' => '#ff9800',
        'success' => '#4CAF50',
        'info' => '#2196F3',
    ];
    $color = $colors[$type] ?? '#f44336';

    echo '
    <div id="alertBox" style="
        position: fixed;
        top: 5em;
        right: 20px;
        min-width: 20em;
        max-width: 50rem;
        padding: 15px 20px;
        background-color: ' . $color . ';
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-family: Arial, sans-serif;
        z-index: 9999;
        opacity: 1;
        transition: opacity 0.5s ease-out;
    ">
        ' . htmlspecialchars($message) . '
    </div>

    <script>
    setTimeout(function() {
        var alertBox = document.getElementById("alertBox");
        if (alertBox) {
            alertBox.style.opacity = "0";
            setTimeout(function() {
                alertBox.remove();
            }, 500);
        }
    }, 5000); // Hide after 5 seconds
    </script>
    ';
}
function checkEmail($conn, $emailToCheck){ 
    $stmt = $conn->prepare("SELECT id FROM Users WHERE email = ?");
    $stmt->bind_param("s", $emailToCheck);
    $stmt->execute();
    $stmt->store_result();
    $status = false;
    if ($stmt->num_rows > 0) {
       $status = true;
    }
    $stmt->close();
    return $status;
}


?>