<?php
// File: register_process.php

// Database connection details
$host = "localhost";  // Database host
$dbname = "survey_link";  // Database name
$username = "root";  // Database username
$password = "";  // Database password (replace with your actual password)

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name     = trim($_POST['first_name']);
    $last_name      = trim($_POST['last_name']);
    $middle_name    = trim($_POST['middle_name'] ?? '');
    $sex            = $_POST['sex'];
    $civil_status   = $_POST['civil_status'];
    $email          = trim($_POST['email']);
    $mobile         = trim($_POST['mobile']);
    $password       = $_POST['password'];
    $confirm        = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm) {
        die("Passwords do not match.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (!preg_match("/^[0-9]{10,15}$/", $mobile)) {
        die("Invalid mobile number.");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users 
        (first_name, last_name, middle_name, sex, civil_status, email, mobile_number, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssss", 
        $first_name, 
        $last_name, 
        $middle_name, 
        $sex, 
        $civil_status, 
        $email, 
        $mobile, 
        $hashed_password
    );

    if ($stmt->execute()) {
        echo "Registration successful. <a href='login_page.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: signup_page.php");
    exit();
}
?>
