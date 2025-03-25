<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Retrieve user from database
    $query = "SELECT id, name, phone, password, role FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id, $name, $phone, $hashed_password, $role);
        mysqli_stmt_fetch($stmt);

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_phone"] = $phone;
            $_SESSION["user_role"] = $role;

            // Role-based redirection
            switch ($role) {
                case "admin":
                    header("Location: admin_dashboard.php");
                    break;
                case "technical":
                    header("Location: technical_dashboard.php");
                    break;
                default:
                    header("Location: booking_trip.php");
                    break;
            }
            exit();
        } else {
            $_SESSION["error"] = "Invalid email or password.";
        }
    } else {
        $_SESSION["error"] = "Invalid email or password.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login Page</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="container login-container">
    <div class="login-form">
      <h2>Login</h2>
      <?php if (isset($_SESSION["error"])): ?>
        <p class="error"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
      <?php endif; ?>
      <form action="login.php" method="post">
        <div class="input-group">
          <input type="email" name="email" placeholder="Enter your email" required />
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Enter your password" required />
        </div>
        <button type="submit">Login</button>
      </form>
      <p>
        <br>
        Don't have an account?
        <a href="register.php">Sign Up</a>
      </p>
    </div>
    <div class="welcome-text">
      <h1>WELCOME BACK!</h1>
      <p>Dapo Travels securing your travels.</p>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
