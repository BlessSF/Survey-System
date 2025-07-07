<?php
// Start session and check user role
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'barangay_official') {
    header("Location: login_page.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'survey_link');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch surveys created by the Barangay Official
$sql = "SELECT * FROM surveys WHERE barangay_official_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$survey_result = $stmt->get_result();

// Fetch the users (citizens) to whom the survey can be assigned
$sql_users = "SELECT * FROM users WHERE role = 'user'";  // Assuming citizens have the role 'user'
$users_result = $conn->query($sql_users);

// Handle the form submission to assign the survey
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure a survey is selected
    if (isset($_POST['survey_id']) && isset($_POST['users'])) {
        $survey_id = $_POST['survey_id'];
        
        // Loop through the selected users and assign the survey
        foreach ($_POST['users'] as $user_id) {
            $assign_sql = "INSERT INTO survey_assignments (survey_id, citizen_id) VALUES (?, ?)";
            $stmt_assign = $conn->prepare($assign_sql);
            $stmt_assign->bind_param("ii", $survey_id, $user_id);
            $stmt_assign->execute();
        }
        
        echo "Survey assigned successfully!";
    } else {
        echo "Please select a survey and users to assign.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Survey to Users</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h2>Assign Survey to Users</h2>
    <p>Select the survey and the users to assign the survey:</p>
    
    <!-- Form to Assign Survey -->
    <form method="POST" action="assign_survey.php">
        <!-- Dropdown to Select Survey -->
        <label for="survey_id">Select Survey:</label>
        <select name="survey_id" required>
            <option value="">Select Survey</option>
            <?php while ($survey = $survey_result->fetch_assoc()): ?>
                <option value="<?php echo $survey['id']; ?>"><?php echo htmlspecialchars($survey['title']); ?></option>
            <?php endwhile; ?>
        </select>
        
        <h3>Select Citizens to Assign Survey:</h3>
        <?php while ($user = $users_result->fetch_assoc()): ?>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="users[]" value="<?php echo $user['user_id']; ?>"> 
                    <?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?>
                </label>
            </div>
        <?php endwhile; ?>

        <input type="submit" value="Assign Survey" class="button">
    </form>

    <!-- Back Button to return to Barangay Official Dashboard -->
    <br><br>
    <a href="barangay_official_dashboard.php" class="button">Back to Dashboard</a>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
