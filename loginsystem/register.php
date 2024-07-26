<?php
// Include the database connection file
include 'db_connection.php';

// This section can be used to handle form submissions or other PHP logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form submission
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security

    // Handle the profile picture upload
    $profile_picture = "";
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploaded_file/';
        
        // Check if the uploads directory exists, if not create it
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $profile_picture = $upload_dir . basename($_FILES['profile_picture']['name']);
        if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture)) {
            die('Failed to move uploaded file.');
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, role, password_hash, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $role, $password, $profile_picture);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to signup-success.html
        header("Location: signup-success.html");
        exit(); // Ensure the script stops executing after the redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registration Page | Ifeanyi Ayodeji</title>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

</head>

<body>
    <div class="login-container">
        <div class="signup-form" id="signupForm">
            <div class="logo-container">
                <img src="bnsl.png" alt="Logo" class="logo">
            </div>
            <h1>Create An Account</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="vendor">Vendor</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>

</html>
