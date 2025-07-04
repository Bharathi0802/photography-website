<?php

session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}
?>
<html>
    <head>
        <style>
            body {
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
        }
        .container {
      background-color: white;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    .container h2 {
      margin-bottom: 24px;
      color: #333;
    }
    .container ul {
        list-style: none;
    }
    .container a {
        display: block;
      margin: 12px 0;
      padding: 12px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }
    .container a:hover {
      background-color:rgb(1, 62, 127);
    }
    .container a.logout {
      background-color:rgb(212, 52, 68);
    }

    .container a.logout:hover {
      background-color:rgb(164, 40, 57);
    }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Welcome to Admin Dashboard</h2>
            <ul>
                <li><a href="view_messages.php">View Contact Messages</a></li>
                <li><a href="view_bookings.php">View Bookings</a></li>
                <a href="manage_availability.php" class="dashboard-btn">Manage Availability Calendar</a>
                <li><a href="logout.php" class="logout">Logout</a></li>
            </ul>
        </div>
    </body>
</html>
