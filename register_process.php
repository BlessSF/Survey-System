<?php
// File: register_process.php

// Database connection details
$host = "localhost";
$dbname = "survey_link";
$username = "root";
$password = "";

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form fields
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

    // Check if the email already exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Error: This email is already registered.");
    }
    $stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user (without the username column)
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
        // Store success message in the session and redirect back to the signup page
        session_start();
        $_SESSION['success_message'] = "Registration successful. Please log in.";
        header("Location: signup_page.php");  // Redirect to signup page with success message
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
