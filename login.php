<?php
session_start();
include 'db.php';

// Clear any previous session before new login

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE mobile='$mobile'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_mobile'] = $row['mobile'];

            // ✅ Redirect to login page with success param (toast)
            header("Location: login.php?login_success=1");
            exit();
        } else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('Mobile number not registered!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="auth.css">
    <link rel="stylesheet" href="toast.css">
</head>
<body class="login-page">

    <?php
    if (isset($_GET['logout'])) {
        echo "<div id='toast' class='error'>Logged out successfully</div>";
    } elseif (isset($_GET['login_success'])) {
        echo "<div id='toast' class='success'>Login successful!</div>";
    }
    ?>

    <div class="form-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="text" name="mobile" placeholder="Mobile Number" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>

    <script>
        const toast = document.getElementById("toast");
        if (toast) {
            // Show toast animation
            setTimeout(() => {
                toast.classList.add("show");
            }, 100);

            // Hide toast and redirect to index
            setTimeout(() => {
                toast.classList.remove("show");
                if (toast.classList.contains("success")) {
                    window.location.href = "index.php";
                }
            }, 1500);
        }
    </script>

</body>
</html>