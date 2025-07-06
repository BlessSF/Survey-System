<?php
// Start the session to access user information
session_start();

// Check if the user is logged in and is a Barangay Official
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'barangay_official') {
    header("Location: login_page.php");  // Redirect to login page if not logged in or not a barangay official
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
    <title>Barangay Official Dashboard</title>
    <link rel="stylesheet" href="css/front_page.css">
</head>
<body>

<div class="container">
    <h2>Welcome, Barangay Official</h2>
    <p>Hi, <?php echo htmlspecialchars($first_name) . " " . htmlspecialchars($last_name); ?>!</p>
    
    <div class="dashboard-actions">
        <a href="create_survey.php" class="button">Create Survey</a>
        <!-- You can add more actions here as needed (view surveys, manage responses, etc.) -->
    </div>

    <div class="dashboard-actions">
        <a href="logout.php" class="button">Logout</a>
    </div>
</div>

</body>
</html>
