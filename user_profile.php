<?php
// Start the session to access user information
session_start();

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");  // Redirect to login page if not logged in
    exit();
}

// User data from session
$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$role = $_SESSION['role'];
$email = $_SESSION['email'];
$status = $_SESSION['status'];
$middle_name = isset($_SESSION['middle_name']) ? $_SESSION['middle_name'] : 'Not provided';
$sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 'Not provided';
$civil_status = isset($_SESSION['civil_status']) ? $_SESSION['civil_status'] : 'Not provided';
$mobile_number = isset($_SESSION['mobile_number']) ? $_SESSION['mobile_number'] : 'Not provided';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/front_page.css">
</head>
<body>

<div class="container">
    <h2>User Profile</h2>

    <div class="profile-info">
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></p>
        <p><strong>Middle Name:</strong> <?php echo htmlspecialchars($middle_name); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></p>
        <p><strong>Role:</strong> <?php echo ucfirst($role); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($status); ?></p>
        <p><strong>Sex:</strong> <?php echo ucfirst($sex); ?></p>
        <p><strong>Civil Status:</strong> <?php echo ucfirst($civil_status); ?></p>
        <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($mobile_number); ?></p>
    </div>

    <!-- Go to Form Button -->
    <a href="form_page.php" class="button">Go to Form</a>

    <!-- Logout Button -->
    <a href="logout.php" class="button">Logout</a>
</div>

</body>
</html>
