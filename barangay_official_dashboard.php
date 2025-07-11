<?php
// Start session and check user role
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'barangay_official') {
    header("Location: login_page.php");
    exit();
}

$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

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
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Official Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h2>Welcome, Barangay Official</h2>
    <p>Hi, <?php echo htmlspecialchars($first_name) . " " . htmlspecialchars($last_name); ?>!</p>
    
    <div class="dashboard-actions">
        <a href="create_survey.php" class="button">Create Survey</a>
        <a href="assign_survey.php" class="button">Assign Survey to Citizens</a>  <!-- Link to Assign Survey -->
        <a href="logout.php" class="button">Log Out</a>  <!-- Log Out Button -->
    </div>

    <h3>Your Surveys:</h3>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Survey Title</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <a href="edit_survey.php?id=<?php echo $row['id']; ?>" class="button">Edit</a>
                            <a href="delete_survey.php?id=<?php echo $row['id']; ?>" class="button">Delete</a>
                            <a href="view_responses.php?survey_id=<?php echo $row['id']; ?>" class="button">View Responses</a>  <!-- Link to View Responses -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No surveys created yet. Click on "Create Survey" to get started.</p>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="back-button-container">
        <a href="index.php" class="button">Back to Home</a>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
