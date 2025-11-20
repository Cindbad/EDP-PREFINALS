<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $user['fullname']; ?>!</h1>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Gender: <?php echo $user['gender']; ?></p>
    <p>City: <?php echo $user['city']; ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
