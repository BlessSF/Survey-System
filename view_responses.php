<?php
// Start session and check user role
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'barangay_official') {
    header("Location: login_page.php");
    exit();
}

// Ensure survey_id is passed
if (!isset($_GET['survey_id']) || empty($_GET['survey_id'])) {
    echo "Survey ID is missing!";
    exit();
}

$survey_id = $_GET['survey_id'];  // Get the survey ID from URL

// Database connection
$conn = new mysqli('localhost', 'root', '', 'survey_link');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the survey details
$sql = "SELECT * FROM surveys WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $survey_id);
$stmt->execute();
$survey_result = $stmt->get_result()->fetch_assoc();

if (!$survey_result) {
    echo "Survey not found!";
    exit();
}

// Fetch all responses for this survey
$sql_responses = "SELECT sr.answer, u.first_name, u.last_name, sq.question
                  FROM survey_responses sr
                  JOIN survey_questions sq ON sr.question_id = sq.id
                  JOIN users u ON sr.citizen_id = u.user_id
                  WHERE sr.survey_id = ?";
$responses_stmt = $conn->prepare($sql_responses);
$responses_stmt->bind_param("i", $survey_id);
$responses_stmt->execute();
$responses_result = $responses_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Responses</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h2>Survey Responses for: <?php echo htmlspecialchars($survey_result['title']); ?></h2>
    <p><?php echo htmlspecialchars($survey_result['description']); ?></p>

    <?php if ($responses_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Citizen Name</th>
                    <th>Question</th>
                    <th>Answer</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($response = $responses_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($response['first_name']) . ' ' . htmlspecialchars($response['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($response['question']); ?></td>
                        <td><?php echo htmlspecialchars($response['answer']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No responses yet for this survey.</p>
    <?php endif; ?>

    <br>
    <a href="barangay_official_dashboard.php" class="button">Back to Dashboard</a>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
