<?php
include 'db.php';

$result = $conn->query("SELECT date, status FROM availability ORDER BY date ASC");
$events = [];

while ($row = $result->fetch_assoc()) {
    $color = match($row['status']) {
        'Booked' => 'red',
        'Unavailable' => 'orange',
        'Available' => 'green',
        default => 'gray'
    };

    $events[] = [
        'title' => $row['status'],
        'start' => $row['date'],
        'color' => $color
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
?>