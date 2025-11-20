<?php
session_start();
include 'config.php'; // your database connection file

$success = ''; // message to show after registration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $city = $_POST['city'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $stmt->close();
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, gender, city, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssss", $fullname, $email, $hashed_password, $gender, $city);
            if ($stmt->execute()) {
                $success = "Account registered successfully!";
            } else {
                $error = "Database error: " . $stmt->error;
            }
        }
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
.right h2 {
  text-align: center;
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 30px;
  letter-spacing: 1px;
}
label {
  font-size: 1rem;
  font-weight: 500;
  margin-top: 15px;
  display: block;
}
input.form-control-lg {
  width: 100%;
  font-size: 1rem;
  padding: 12px;
  margin-top: 5px;
  border: none;
  border-radius: 8px;
  outline: none;
  background: rgba(255,255,255,0.08);
  color: white;
  box-sizing: border-box;
  cursor: text; /* I-beam for text inputs */
}
select.form-control-lg {
  width: 100%;
  font-size: 1rem;
  padding: 12px;
  margin-top: 5px;
  border: none;
  border-radius: 8px;
  outline: none;
  background: rgba(255,255,255,0.08);
  color: white;
  box-sizing: border-box;
  cursor: pointer; /* pointer for dropdown */
  appearance: none;
  -webkit-appearance: none;
  backdrop-filter: blur(2px);
}
input.form-control-lg:focus,
select.form-control-lg:focus {
  box-shadow: 0 0 8px #36A2EB;
  background-color: rgba(255, 255, 255, 0.15);
}
select.form-control-lg option {
  background: #1d1f27;
  color: white;
  padding: 10px;
}
.btn-custom {
  width: 100%;
  margin-top: 25px;
  padding: 16px;
  text-transform: uppercase;
  border-radius: 12px;
  font-size: 20px;
  font-weight: 600;
  cursor: pointer; /* pointer for button */
  background: #36A2EB;
  color: #fff;
  border: none;
  transition: 0.3s ease;
}
.btn-custom:hover {
  background: #2e89d4;
}
.right p.small {
  text-align: center;
  margin-top: 20px;
  font-size: 1.1rem;
}
.right a {
  color: #36A2EB;
  text-decoration: none;
}
.right a:hover {
  text-decoration: underline;
}
.error-msg {
  color: #ff6b6b;
  text-align: center;
  margin-bottom: 10px;
}
.success-msg {
  color: #4caf50;
  text-align: center;
  margin-bottom: 10px;
}
</style>
</head>
<body>
<div class="container-box">
  <div class="right">
    <h2>Sign Up</h2>
    <?php 
    if(isset($error)) { echo "<p class='error-msg'>$error</p>"; } 
    if($success != '') { echo "<p class='success-msg'>$success</p>"; } 
    ?>
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
