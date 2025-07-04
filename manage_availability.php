<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $status = $_POST['status'];

    // Upsert: update if exists, else insert
    $stmt = $conn->prepare("INSERT INTO availability (date, status) VALUES (?, ?)
                            ON DUPLICATE KEY UPDATE status = ?");
    $stmt->bind_param("sss", $date, $status, $status);
    $stmt->execute();
}

// Fetch existing availability
$availability = [];
$result = $conn->query("SELECT * FROM availability");
while ($row = $result->fetch_assoc()) {
    $availability[$row['date']] = $row['status'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Availability</title>
    <style>
        .booked { color: red; }
        .unavailable { color: orange; }
        .available { color: green; }
    </style>
</head>
<body>
<h2>Manage Availability</h2>

<form method="POST">
    <label>Select Date:</label>
    <input type="date" name="date" required>
    <select name="status" required>
        <option value="Available">Available</option>
        <option value="Booked">Booked</option>
        <option value="Unavailable">Unavailable</option>
    </select>
    <button type="submit">Save</button>
</form>

<h3>Availability Summary</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Date</th>
        <th>Status</th>
    </tr>
    <?php foreach ($availability as $date => $status) { ?>
    <tr>
        <td><?= $date ?></td>
        <td class="<?= strtolower($status) ?>"><?= $status ?></td>
    </tr>
    <?php } ?>
</table>

<a href="dashboard.php">← Back to Dashboard</a>
</body>
</html>
