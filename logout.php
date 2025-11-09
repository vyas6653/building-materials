<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Delete the session cookie (important for localhost)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logging Out...</title>
  <link rel="stylesheet" href="auth.css">
  <!-- Redirect to login.php after 2 seconds -->
  <meta http-equiv="refresh" content="2;url=login.php">
</head>
<body class="logout-page">
  <div class="form-container">
    <h2>You have been logged out.</h2>
    <p>Redirecting to login page...</p>
  </div>
</body>
</html>