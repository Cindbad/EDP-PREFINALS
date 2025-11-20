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

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            header("Location: dashboard.php");
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Email not registered!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
/* Same CSS as signup page */
body {
  margin: 0;
  padding: 0;
  height: 100vh;
  background: url("https://static.vecteezy.com/system/resources/previews/007/972/775/non_2x/dark-black-and-gray-blurred-background-has-a-little-abstract-light-free-photo.jpg") no-repeat center center/cover;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: Arial, sans-serif;
}
.container-box { width: 650px; padding: 50px; background: rgba(0,0,0,0.65); border-radius: 18px; box-sizing: border-box; }
.right { width: 100%; }
.right h2 { text-align: center; font-size: 2.6rem; font-weight: 700; margin-bottom: 30px; letter-spacing: 1px; }
label { font-size: 1.1rem; font-weight: 500; margin-bottom: 5px; display: block; margin-top: 15px; }
input.form-control-lg, select.form-control-lg { width: 100%; font-size: 1.1rem; padding: 14px; margin-top: 5px; border: none; border-radius: 8px; outline: none; box-sizing: border-box; }
input.form-control-lg:focus, select.form-control-lg:focus { box-shadow: 0 0 10px #ffffff50; background-color: rgba(255,255,255,0.1); color: white; }
.btn-custom { width: 100%; margin-top: 25px; padding: 16px; text-transform: uppercase; border-radius: 12px; font-size: 20px; font-weight: 600; cursor: pointer; background: transparent; color: #ffffffb0; border: 2px solid #ffffff80; transition: 0.4s ease; }
.btn-custom:hover { background: #ffffff; color: #000; border: 2px solid #ccc; box-shadow: 0 0 20px #ccc; }
.right p.small { text-align: center; margin-top: 30px; font-size: 1.1rem; }
.right a { color: #fff; font-size: 1.1rem; }
</style>
</head>
<body>
<div class="container-box">
  <div class="right">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
    <?php if(isset($_SESSION['success'])) { echo "<p style='color:lightgreen; text-align:center;'>".$_SESSION['success']."</p>"; unset($_SESSION['success']); } ?>
    <form method="POST" action="">
      <label>Email</label>
      <input type="email" name="email" class="form-control-lg" required>

      <label>Password</label>
      <input type="password" name="password" class="form-control-lg" required>

      <button type="submit" class="btn-custom">Login</button>
    </form>
    <p class="small">Don't have an account? <a href="signup.php">Sign up here</a></p>
  </div>
</div>
</body>
</html>
