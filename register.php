<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkSql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Mobile number already registered!'); window.location='login.php';</script>";
    } else {
        $sql = "INSERT INTO users (name, mobile, password) VALUES ('$name', '$mobile', '$hashedPassword')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Error: Registration failed.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="auth.css">
</head>

    <body class="register-page">
    <div class="form-container">
        <h2>Register</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required><br>
            <input type="text" name="mobile" placeholder="Mobile Number" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>