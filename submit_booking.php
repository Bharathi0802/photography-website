<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

// Sanitize inputs
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$date = $_POST['booking_date'];
$time = $_POST['booking_time'];
$package = $_POST['package'];
$location = htmlspecialchars($_POST['location']);
$special = htmlspecialchars($_POST['special_requests']);

// Insert
$sql = "INSERT INTO bookings (name, email, phone, booking_date, booking_time, package, location, special_requests)
        VALUES ('$name', '$email', '$phone', '$date', '$time', '$package', '$location', '$special')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Booking submitted successfully!'); window.location.href='index.html';</script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

