<?php
// Start session and check user role
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
    header("Location: login_page.php");
    exit();
}

$citizen_id = $_SESSION['user_id'];  // Get the citizen ID

// Database connection
$conn = new mysqli('localhost', 'root', '', 'survey_link');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch citizen details
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $citizen_id);
$stmt->execute();
$citizen = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h2>Your Profile</h2>
    <form method="POST" action="update_profile.php">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($citizen['first_name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="middle_name">Middle Name:</label>
            <input type="text" name="middle_name" value="<?php echo htmlspecialchars($citizen['middle_name']); ?>">
        </div>

        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($citizen['last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="sex">Sex:</label>
            <select name="sex">
                <option value="Male" <?php echo $citizen['sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $citizen['sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="civil_status">Civil Status:</label>
            <select name="civil_status">
                <option value="Single" <?php echo $citizen['civil_status'] == 'Single' ? 'selected' : ''; ?>>Single</option>
                <option value="Married" <?php echo $citizen['civil_status'] == 'Married' ? 'selected' : ''; ?>>Married</option>
                <option value="Divorced" <?php echo $citizen['civil_status'] == 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                <option value="Widowed" <?php echo $citizen['civil_status'] == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
            </select>
        </div>

        <div class="form-group">
            <label for="mobile_number">Mobile Number:</label>
            <input type="text" name="mobile_number" value="<?php echo htmlspecialchars($citizen['mobile_number']); ?>" required>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" name="status" value="<?php echo htmlspecialchars($citizen['status']); ?>" disabled>
        </div>

        <div class="form-group">
            <label for="created_at">Created At:</label>
            <input type="text" name="created_at" value="<?php echo htmlspecialchars($citizen['created_at']); ?>" disabled>
        </div>

        <div class="form-group">
            <label for="updated_at">Updated At:</label>
            <input type="text" name="updated_at" value="<?php echo htmlspecialchars($citizen['updated_at']); ?>" disabled>
        </div>

        <div class="form-group">
            <input type="submit" class="button" value="Update Profile">
        </div>
    </form>

    <a href="citizen_dashboard.php" class="button">Back to Dashboard</a>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
