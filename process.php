<?php
session_start();


$password = $filter_input(INPUT_POST, 'password');
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "login";

// Establish database connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Disable autocommit
mysqli_autocommit($conn, false);

// Perform your database operations
$query1 = "INSERT INTO tb_user (username, password) VALUES ('$username', '$password')";
$result1 = mysqli_query($conn, $query1);

$query2 = "UPDATE user SET username = 'newvalue' WHERE id = 1";
$result2 = mysqli_query($conn, $query2);

// Check if all queries were successful
if ($result1 && $result2) {
    // Commit the transaction
    mysqli_commit($conn);
    echo "Transaction committed successfully.";
} else {
    // Rollback the transaction
    mysqli_rollback($conn);
    echo "Transaction failed. Changes rolled back.";
}
// Handle login request
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform login validation
    $query = "SELECT * FROM tb_user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Login successful
        $_SESSION['username'] = $username;
        header("Location: index.html");
        exit();
    } else {
        // Invalid credentials
        echo "Invalid username or password";
    }
}

// Handle registration request
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $query = "SELECT * FROM tb_user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Username already exists
        echo "Username already exists. Please choose a different username.";
    } else {
        // Insert new user into database
        $insertQuery = "INSERT INTO tb_user (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $insertQuery)) {
            // Registration successful
            $_SESSION['username'] = $username;
            header("Location: index.html");
            exit();
        } else {
            // Registration failed
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("Location: login.html");
    exit();
}

mysqli_close($conn);
?>