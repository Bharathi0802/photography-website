<?php
include 'db.php';

$availability = [];
$result = $conn->query("SELECT * FROM availability ORDER BY date ASC");
while ($row = $result->fetch_assoc()) {
    $availability[] = $row;
}
?>

<table>
  <tr>
    <th>Date</th>
    <th>Status</th>
  </tr>
  <?php foreach ($availability as $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['date']) ?></td>
      <td class="<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
    </tr>
  <?php endforeach; ?>
</table>
