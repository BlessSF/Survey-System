<?php
// Start session and check user role
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
    header("Location: login_page.php");
    exit();
}

$survey_id = $_GET['survey_id'];  // Get the survey ID

// Database connection
$conn = new mysqli('localhost', 'root', '', 'survey_link');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch survey details
$sql = "SELECT * FROM surveys WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $survey_id);
$stmt->execute();
$survey_result = $stmt->get_result()->fetch_assoc();

// Fetch the questions for the survey
$question_sql = "SELECT * FROM survey_questions WHERE survey_id = ?";
$question_stmt = $conn->prepare($question_sql);
$question_stmt->bind_param("i", $survey_id);
$question_stmt->execute();
$questions_result = $question_stmt->get_result();

// Handle survey submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store the responses
    foreach ($_POST['answers'] as $question_id => $answer) {
        $insert_answer_sql = "INSERT INTO survey_responses (survey_id, question_id, citizen_id, answer) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_answer_sql);
        $insert_stmt->bind_param("iiis", $survey_id, $question_id, $_SESSION['user_id'], $answer);
        $insert_stmt->execute();
    }

    // Set a success message flag
    $survey_submitted = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h2>Survey: <?php echo htmlspecialchars($survey_result['title']); ?></h2>
    <p><?php echo htmlspecialchars($survey_result['description']); ?></p>

    <!-- Form to submit answers -->
    <form method="POST" action="survey_form_user.php?survey_id=<?php echo $survey_id; ?>">
        <?php while ($question = $questions_result->fetch_assoc()): ?>
            <div class="form-group">
                <label for="question_<?php echo $question['id']; ?>"><?php echo htmlspecialchars($question['question']); ?></label>
                <textarea name="answers[<?php echo $question['id']; ?>]" id="question_<?php echo $question['id']; ?>" required></textarea>
            </div>
        <?php endwhile; ?>

        <br>
        <input type="submit" value="Submit Survey">
    </form>

    <!-- Floating success message -->
    <?php if (isset($survey_submitted) && $survey_submitted): ?>
        <div class="success-message" id="successMessage">
            Survey submitted successfully!
        </div>

        <!-- JavaScript to show and hide the success message -->
        <script>
            // Show the success message
            var successMessage = document.getElementById('successMessage');
            successMessage.style.display = 'block';

            // Hide the success message after 3 seconds
            setTimeout(function() {
                successMessage.style.display = 'none';
                // Redirect to the user profile page after 3 seconds
                window.location.href = 'user_profile.php';  // Redirect to the User Profile Page
            }, 3000);
        </script>
    <?php endif; ?>

</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
