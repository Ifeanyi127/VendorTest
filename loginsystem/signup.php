<?php session_start();
require_once('includes/config.php');

//Code for Registration 
if(isset($_POST['submit']))
{
    $fname=$_POST['fname'];
    $lname=$_POST['lname'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $contact=$_POST['contact'];
$sql=mysqli_query($con,"select id from users where email='$email'");
$row=mysqli_num_rows($sql);
if($row>0)
{
    echo "<script>alert('Email id already exist with another account. Please try with other email id');</script>";
} else{
    $msg=mysqli_query($con,"insert into users(fname,lname,email,password,contactno) values('$fname','$lname','$email','$password','$contact')");

if($msg)
{
    echo "<script>alert('Registered successfully');</script>";
    echo "<script type='text/javascript'> document.location = '/xampp/htdocs/User/loginsystem/Login/login.php'; </script>";
}
}
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registration Page | Ifeanyi Ayodeji</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <script type="text/javascript">
    function checkpass()
{
if(document.signup.password.value!=document.signup.confirmpassword.value)
{
alert(' Password and Confirm Password field does not match');
document.signup.confirmpassword.focus();
return false;
}
return true;
} 

</script> 
</head>

<body>
    <div class="login-container">
        <div class="signup-form" id="signupForm">
            <div class="logo-container">
                <img src="bnsl.png" alt="Logo" class="logo">
            </div>
            <h1>Create An Account</h1>
            <form method="post" name="signup" onsubmit="return checkpass();">
                <div class="form-group">
                    <input type="text" id="fname" name="fname" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <input type="text" id="lname" name="lname" placeholder="Last Name" required>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
              <div class="form-group">
              <input type="text" id="contact" name="contact" placeholder="Role" required maxlength="10">
              </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="at least one number and one uppercase and lowercase letter, and at least 6 or more characters" required>
                </div>
                <div class="form-group">
                    <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="at least one number and one uppercase and lowercase letter, and at least 6 or more characters" required>
                </div>
                <button type="submit" name="submit">Create Account</button>
            </form>
          <div class="small"><a href="/xampp/htdocs/User/loginsystem/Login/login.php">Have an account? Go to login</a></div>
            <div class="small"><a href="index.php">Back to Home</a></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>