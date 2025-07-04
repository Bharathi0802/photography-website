<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Database code (keep same)
include 'db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Save to database
$sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $message);

// Send email using PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  
    $mail->SMTPAuth = true;
    $mail->Username = 'iniyarul.1500@gmail.com';     // your Gmail
    $mail->Password = 'ukbx atdc tlae lvsu';       // App Password (not Gmail login)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('iniyarul.1500@gmail.com', 'Photography Site');
    $mail->addAddress('iniyarul.1500@gmail.com');   // Receiver

    $mail->Subject = 'New Contact Message';
    $mail->Body = "Name: $name\nEmail: $email\nMessage:\n$message";

    $mail->send();
    $emailStatus = true;
} catch (Exception $e) {
    $emailStatus = false;
}

// Feedback to user
if ($stmt->execute()) {
    $alertMsg = $emailStatus ? 'Message submitted and emailed!' : 'Saved, but email failed.';
    echo "<script>alert('$alertMsg'); window.history.back();</script>";
} else {
    echo "<script>alert('Something went wrong.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
