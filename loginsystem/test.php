<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_SERVER', 'localhost');
define('DB_USER', 'funnypar_loginsystem');
define('DB_PASS', 'January2021###');
define('DB_NAME', 'funnypar_loginsystem');

$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Connection successful!";
}

?>