<?php
session_start();

// Database connection (Update with your actual database credentials)
$host = "localhost";  // Database host
$dbname = "survey_link";  // Database name
$username = "root";  // Database username
$password = "";  // Database password (replace with your actual password)

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure both fields are not empty
    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo "Both fields are required.";
        exit();
    }

    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check citizens table
    $sql = "SELECT user_id, email, password, first_name, last_name, 'citizen' AS role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists in users table (citizens)
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['status'] = 'active';

            // Redirect to citizen dashboard
            header("Location: citizen_dashboard.php");
            exit();
        }
    }

    // Check barangay_officials table (barangay officials)
    $sql = "SELECT id, email, password, first_name, last_name, 'barangay_official' AS role FROM barangay_officials WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists in barangay_officials table
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === '12345') {  // Default password for barangay officials
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['status'] = 'active';

            // Redirect to barangay official dashboard
            header("Location: barangay_official_dashboard.php");
            exit();
        }
    }

    // Check admins table (local government)
    $sql = "SELECT id, email, password, first_name, last_name, 'local_government' AS role FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists in admins table
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === '12345') {  // Default password for admin
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['status'] = 'active';

            // Redirect to local government dashboard
            header("Location: local_government_dashboard.php");
            exit();
        }
    }

    // If none of the above checks pass, show invalid login
    echo "Invalid email or password.";
    
    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
