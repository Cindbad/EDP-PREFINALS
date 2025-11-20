<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $city = $_POST['city'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hashed_confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, confirm_password, gender, city) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullname, $email, $hashed_password, $hashed_confirm_password, $gender, $city);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful. Please login.";
            header("Location: login.php");
        } else {
            $error = "Email already exists!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up</title>
<style>
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
.container-box {
  width: 650px;
  padding: 50px;
  background: rgba(0,0,0,0.65);
  border-radius: 18px;
  box-sizing: border-box;
}
.right { width: 100%; }
.right h2 {
  text-align: center;
  font-size: 2.6rem;
  font-weight: 700;
  margin-bottom: 30px;
  letter-spacing: 1px;
}
label {
  font-size: 1.1rem;
  font-weight: 500;
  margin-bottom: 5px;
  display: block;
  margin-top: 15px;
}
input.form-control-lg,
select.form-control-lg {
  width: 100%;
  font-size: 1.1rem;
  padding: 14px;
  margin-top: 5px;
  border: none;
  border-radius: 8px;
  outline: none;
  box-sizing: border-box;
}
input.form-control-lg:focus,
select.form-control-lg:focus {
  box-shadow: 0 0 10px #ffffff50;
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
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
  background: transparent;
  color: #ffffffb0;
  border: 2px solid #ffffff80;
  transition: 0.4s ease;
}
.btn-custom:hover {
  background: #ffffff;
  color: #000;
  border: 2px solid #ccc;
  box-shadow: 0 0 20px #ccc;
}
.right p.small {
  text-align: center;
  margin-top: 30px;
  font-size: 1.1rem;
}
.right a {
  color: #fff;
  font-size: 1.1rem;
}
</style>
</head>
<body>
<div class="container-box">
  <div class="right">
    <h2>Sign Up</h2>
    <?php if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
    <form method="POST" action="">
      <label>Full Name</label>
      <input type="text" name="fullname" class="form-control-lg" required>

      <label>Email</label>
      <input type="email" name="email" class="form-control-lg" required>

      <label>Password</label>
      <input type="password" name="password" class="form-control-lg" required>

      <label>Confirm Password</label>
      <input type="password" name="confirm_password" class="form-control-lg" required>

      <label>Gender</label>
      <select name="gender" class="form-control-lg" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>

      <label>City</label>
      <input type="text" name="city" class="form-control-lg" required>

      <button type="submit" class="btn-custom">Sign Up</button>
    </form>
    <p class="small">Already have an account? <a href="login.php">Login here</a></p>
  </div>
</div>
</body>
</html>
