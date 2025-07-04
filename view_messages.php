<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle reply
$replySuccess = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'iniyarul.1500@gmail.com'; // your Gmail
        $mail->Password   = 'ukbx atdc tlae lvsu';    // your app password (not your Gmail password)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('iniyarul.1500@gmail.com', 'Photographer');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        $replySuccess = "Reply sent to $to ✅";
    } catch (Exception $e) {
        $replySuccess = "❌ Message could not be sent. Error: {$mail->ErrorInfo}";
    }
}

// Handle delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = $_POST["id"];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: view_messages.php");
    exit();
}

// Fetch messages
$result = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>

<h2>Contact Messages</h2>
<?php if ($replySuccess): ?>
<p style="color:green;"><?= $replySuccess ?></p>
<?php endif; ?>
<table border="1" cellpadding="10">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Reply</th>
        <th>Submitted</th>
        <th>Delete</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="to" value="<?= htmlspecialchars($row['email']) ?>">
                <input type="text" name="subject" placeholder="Subject" required><br>
                <textarea name="body" placeholder="Your reply" rows="3" cols="30" required></textarea><br>
                <button type="submit" name="reply">Send</button>
            </form>
        </td>
        <td><?= $row['submitted_at'] ?></td>
        <td>
            <form method="POST" onsubmit="return confirm('Delete this message?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete">Delete</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<a href="dashboard.php" style="font-size: large;">← Back to Dashboard</a>
