<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('includes/config.php');
// Code for login 
if (isset($_POST['login'])) {
    $password = $_POST['password'];
    $dec_password = $password;
    $useremail = $_POST['uemail'];
    $ret = mysqli_query($con, "SELECT id, fname FROM users WHERE email='$useremail' and password='$dec_password'");
    $num = mysqli_fetch_array($ret);
    if ($num > 0) {
        $_SESSION['id'] = $num['id'];
        $_SESSION['name'] = $num['fname'];
        header("location:welcome.php");
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign In | Ifeanyi Ayodeji</title>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel='stylesheet'>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background: url('bg.jpg') no-repeat;
            background-size: cover;
            background-position: center;
        }

        .login-container {
            text-align: center;
        }

        .signup-success {
            color: green;
            font-weight: bold;
        }

        .signup-error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="./BNSL.png" alt="Logo">
        <div class="login-form" id="loginForm">
            <h1>Sign In</h1>

            <div class="form-message-container">
                <span>Use your email and password</span>
            </div>

            <form id="signinForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="email" id="email" name="uemail" placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Sign In</button>
                <p id="signinMessage">
                    <?php
                    if (isset($login_error)) {
                        echo $login_error;
                    }
                    ?>
                </p>
            </form>
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
    </div>
</body>
</html>

