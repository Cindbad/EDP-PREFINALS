<?php
include 'db.php';

if(isset($_POST['signup'])){
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fullname, email, password, confirm_password, gender, city)
            VALUES ('$fullname','$email','$hashed_password','$hashed_password','','')";

    if($conn->query($sql) === TRUE){
        echo "<script>alert('Signup successful!'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error: ".$conn->error."'); window.history.back();</script>";
    }
} else {
    // if someone opens signup.php directly
    header("Location: signup.html");
    exit;
}
?>
