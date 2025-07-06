<?php
// Start the session to access user information
session_start();

// Check if the user is logged in and is a Local Government user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'local_government') {
    header("Location: login_page.php");  // Redirect to login page if not logged in or not a local government user
    exit();
}

// User data from session
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Government Dashboard</title>
    <link rel="stylesheet" href="css/front_page.css">
</head>
<body>

<div class="container">
    <h2>Welcome, Local Government</h2>
    <p>Hi, <?php echo htmlspecialchars($first_name) . " " . htmlspecialchars($last_name); ?>!</p>
    
    <div class="dashboard-actions">
        <a href="view_survey_summary.php" class="button">View Survey Summary</a>
        <!-- You can add more actions here like viewing reports, managing projects, etc. -->
    </div>

    <div class="dashboard-actions">
        <a href="logout.php" class="button">Logout</a>
    </div>
</div>

</body>
</html>
