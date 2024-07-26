<?php
session_start();
include_once('includes/config.php');

// Function to securely hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to fetch user from database
    $sql = "SELECT id, fname, password FROM users WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, login successful
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['fname'];
            header('Location: welcome.php');
            exit();
        } else {
            // Password is incorrect
            $login_error = "Invalid email or password. Please try again.";
        }
    } else {
        // User not found
        $login_error = "Invalid email or password. Please try again.";
    }

    // Close statement
    $stmt->close();
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

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
                <input type="email" id="email" name="email" placeholder="Email" required>
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
        </div>
    </div>
</body>
</html>
