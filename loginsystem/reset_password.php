<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('includes/config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $ret = mysqli_query($con, "SELECT * FROM password_resets WHERE token='$token' AND expiry > NOW()");
    $num = mysqli_fetch_array($ret);
    if ($num > 0) {
        if (isset($_POST['reset_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            if ($new_password === $confirm_password) {
                $email = $num['email'];
                mysqli_query($con, "UPDATE users SET password='$new_password' WHERE email='$email'");
                mysqli_query($con, "DELETE FROM password_resets WHERE email='$email'");
                echo "<script>alert('Password reset successfully');</script>";
                echo "<script type='text/javascript'> document.location = '/xampp/htdocs/User/loginsystem/Login/login.php'; </script>";
            } else {
                echo "<script>alert('Passwords do not match');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid or expired token');</script>";
    }
} else {
    echo "<script>alert('No token provided');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Reset Password | Ifeanyi Ayodeji</title>
</head>
<body>
    <div class="login-container">
        <div class="login-form" id="loginForm">
            <h1>Reset Password</h1>
            <form id="resetPasswordForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?token=' . $_GET['token']); ?>" method="post">
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
