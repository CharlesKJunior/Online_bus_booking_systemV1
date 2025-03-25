<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION["error"] = "Passwords do not match!";
        header("Location: register.php");
        exit();
    }

    // Default role for new users
    $role = "passenger"; 

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email or phone already exists
    $check_query = "SELECT id FROM users WHERE email = ? OR phone = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $phone);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION["error"] = "Email or phone already exists!";
    } else {
        // Insert user into database
        $query = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $hashed_password, $role);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["success"] = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION["error"] = "Registration failed. Please try again.";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register Page</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="container register-container">
    <div class="welcome-text">
      <h1>WELCOME BACK!</h1>
      <p>Dapo Travels Securing your Travels.</p>
    </div>
    <div class="register-form">
      <h2>Sign Up</h2>
      <?php if (isset($_SESSION["error"])): ?>
        <p class="error"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
      <?php endif; ?>
      <?php if (isset($_SESSION["success"])): ?>
        <p class="success"><?php echo $_SESSION["success"]; unset($_SESSION["success"]); ?></p>
      <?php endif; ?>
      <form action="register.php" method="post">
        <div class="input-group">
          <input type="text" name="name" placeholder="Enter your full name" required />
        </div>
        <div class="input-group">
          <input type="email" name="email" placeholder="Enter your email" required />
        </div>
        <div class="input-group">
          <input type="text" name="phone" placeholder="Enter your phone number" required />
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Enter your password" required />
        </div>
        <div class="input-group">
          <input type="password" name="confirm_password" placeholder="Confirm password" required />
        </div>
        <button type="submit">Sign Up</button>
      </form>
      <p>
        Already have an account?
        <a href="login.php">Login</a>
      </p>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>
