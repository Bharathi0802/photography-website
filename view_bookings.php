<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Update status
if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

// Delete booking
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: view_bookings.php");
    exit();
}

// Fetch bookings
$result = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Requests</title>
    <style>
        .status-select {
            padding: 5px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
            border: none;
        }

        .status-new {
            background-color: #f1c40f;
        }

        .status-inprocess {
            background-color: #e67e22;
        }

        .status-completed {
            background-color: #2ecc71;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #f2f2f2;
        }

        td, th {
            padding: 10px;
            text-align: left;
        }

        button {
            padding: 5px 10px;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Booking Requests</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Package</th>
        <th>Date</th>
        <th>Location</th>
        <th>Message</th>
        <th>Status</th>
        <th>Actions</th>
        <th>Submitted</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['package']) ?></td>
        <td><?= $row['booking_date'] . ' at ' . date("g:i A", strtotime($row['booking_time'] ?? '00:00:00')) ?></td>
        <td><?= htmlspecialchars($row['location']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['special_requests'])) ?></td>
        <td>
            <?php
            $status = $row['status'];
            $class = '';
            if ($status == 'New') $class = 'status-new';
            elseif ($status == 'In Process') $class = 'status-inprocess';
            elseif ($status == 'Completed') $class = 'status-completed';
            ?>
            <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <select name="status" class="status-select <?= $class ?>" onchange="this.form.submit()">
                    <option value="New" <?= $status == 'New' ? 'selected' : '' ?>>New</option>
                    <option value="In Process" <?= $status == 'In Process' ? 'selected' : '' ?>>In Process</option>
                    <option value="Completed" <?= $status == 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
                <input type="hidden" name="update_status" value="1">
            </form>
        </td>
        <td>
            <form method="post" onsubmit="return confirm('Delete this booking?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete">Delete</button>
            </form>
        </td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php } ?>
</table>
<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
