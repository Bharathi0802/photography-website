<?php
session_start();
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php'; // your db connection file

    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM admin_login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["admin_logged_in"] = true;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "User not found";
    }
}
?>

<!-- HTML Form -->
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
            .login-container {
                background-color: white;
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                width: 400px;
                text-align: center;
            }
    .login-container h2 {
      margin-bottom: 24px;
    }

    .login-container input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      box-sizing: border-box;
    }

    .login-container button {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
    }

    .login-container button:hover {
      background-color:rgb(59, 138, 63);
    }
 </style>
    </head>
    <body>
        <div class="login-container">
        <form method="POST" action="">
    <h2>Admin Login</h2>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
    <p style="color:red;"><?php echo $error; ?></p>
</form>
<p><a href="/photographer/index.html">← Back to Website</a></p>
</div>
    </body>
 </html>
