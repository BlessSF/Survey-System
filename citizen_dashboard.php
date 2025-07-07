<?php
// Start session and check user role
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
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

// Fetch surveys assigned to the citizen
$sql = "SELECT s.id, s.title, s.description, s.created_at
        FROM surveys s
        JOIN survey_assignments sa ON s.id = sa.survey_id
        WHERE sa.citizen_id = ?";
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
    <title>Citizen Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h2>Welcome, Citizen</h2>
    <p>Hi, <?php echo htmlspecialchars($first_name) . " " . htmlspecialchars($last_name); ?>!</p>
    
    <!-- Buttons for profile and log out -->
    <div class="dashboard-actions">
        <a href="user_profile.php" class="button">View Profile</a>  <!-- View Profile Button -->
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
                            <a href="survey_form_user.php?survey_id=<?php echo $row['id']; ?>" class="button">Fill Out Survey</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No surveys assigned yet. Please wait for your assigned surveys.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
