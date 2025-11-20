<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
body {
  margin: 0;
  padding: 0;
  height: 100vh;
  background: #1d1f27;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: Arial, sans-serif;
}
.container-box {
  width: 600px;
  padding: 50px;
  background: rgba(0,0,0,0.65);
  border-radius: 18px;
  box-sizing: border-box;
  box-shadow: 0 0 20px rgba(255,255,255,0.15);
}
h2 {
  text-align: center;
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 30px;
  letter-spacing: 1px;
}
label {
  font-size: 1.1rem;
  font-weight: 500;
  margin-top: 15px;
  display: block;
}

/* INPUTS same as signup fill color */
input.form-control-lg {
  width: 100%;
  font-size: 1.1rem;
  padding: 14px;
  margin-top: 5px;
  border: none;
  border-radius: 8px;
  outline: none;
  background: rgba(255,255,255,0.08); /* copied from signup */
  color: white;
  box-sizing: border-box;
  cursor: text;
}

input.form-control-lg:focus {
  box-shadow: 0 0 10px #36A2EB;
  background-color: rgba(255, 255, 255, 0.15);
}

.btn-custom {
  width: 100%;
  margin-top: 25px;
  padding: 16px;
  text-transform: uppercase;
  border-radius: 12px;
  font-size: 20px;
  font-weight: 600;
  cursor: pointer;
  background: #36A2EB;
  color: #fff;
  border: none;
  transition: 0.3s ease;
}

.btn-custom:hover {
  background: #2e89d4;
}

p.small {
  text-align: center;
  margin-top: 20px;
  font-size: 1.1rem;
}

a {
  color: #36A2EB;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.error-msg {
  color: #ff6b6b;
  text-align: center;
  margin-bottom: 10px;
}
</style>
</head>
<body>
<div class="container-box">
  <h2>Login</h2>
  <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
  <form method="POST" action="">
    <label>Email</label>
    <input type="email" name="email" class="form-control-lg" required>

    <label>Password</label>
    <input type="password" name="password" class="form-control-lg" required>

    <button type="submit" class="btn-custom">Login</button>
  </form>
  <p class="small">Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>
</body>
</html>
