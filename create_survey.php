<?php
// Start the session to access user information
session_start();

// Check if the user is logged in and has the correct role (Barangay Official)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'barangay_official') {
    header("Location: login_page.php");  // Redirect to login page if not logged in or not a Barangay Official
    exit();
}

// Fetch suggested questions from the database
$conn = new mysqli('localhost', 'root', '', 'survey_link');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_suggested_questions = "SELECT * FROM suggested_questions";
$suggested_questions_result = $conn->query($sql_suggested_questions);

// Handle the form submission to create a new survey
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the survey details from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $barangay_official_id = $_SESSION['user_id'];

    // Insert the survey into the database
    $stmt = $conn->prepare("INSERT INTO surveys (barangay_official_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $barangay_official_id, $title, $description);
    $stmt->execute();
    $survey_id = $stmt->insert_id;  // Get the ID of the newly created survey

    // Insert the questions related to the survey
    if (isset($_POST['questions'])) {
        $questions = $_POST['questions'];
        foreach ($questions as $question) {
            $stmt = $conn->prepare("INSERT INTO survey_questions (survey_id, question) VALUES (?, ?)");
            $stmt->bind_param("is", $survey_id, $question);
            $stmt->execute();
        }
    }

    // Close the connection
    $conn->close();

    // Set a success message flag to display success on the same page
    $survey_created = true;  // Flag to indicate that the survey was created successfully
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Survey</title>
    <link rel="stylesheet" href="css/style.css">  <!-- Link to external CSS file -->
</head>
<body>

<div class="container">
    <h2>Create a New Survey</h2>

    <!-- Survey creation form -->
    <form method="POST" action="create_survey.php">
        <!-- Survey Title -->
        <div class="form-group" id="survey-title">
            <label for="title">Survey Title:</label>
            <input type="text" id="title" name="title" required>
        </div>

        <!-- Survey Description -->
        <div class="form-group" id="survey-description">
            <label for="description">Survey Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <!-- Suggested Questions Section -->
        <div class="form-group" id="suggested-questions">
            <label>Suggested Questions:</label>
            <?php if ($suggested_questions_result->num_rows > 0): ?>
                <?php while ($row = $suggested_questions_result->fetch_assoc()): ?>
                    <div>
                        <input type="checkbox" name="questions[]" value="<?php echo $row['question_text']; ?>">
                        <?php echo htmlspecialchars($row['question_text']); ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No suggested questions available.</p>
            <?php endif; ?>
        </div>

        <!-- Custom Questions Section -->
        <div id="questions-container">
            <div class="form-group" id="question-1">
                <label for="question">Question 1:</label>
                <input type="text" name="questions[]" required>
                <button type="button" class="remove-question" onclick="removeQuestion(1)">Remove</button>
            </div>
        </div>

        <!-- Button to dynamically add more questions -->
        <button type="button" onclick="addQuestion()">Add More Questions</button>
        <br><br>

        <!-- Submit the form -->
        <input type="submit" value="Create Survey">
    </form>

    <!-- Show floating success message with text only after survey is created -->
    <?php if (isset($survey_created) && $survey_created): ?>
        <div class="success-message">
            Survey created successfully!
        </div>

        <!-- JavaScript to redirect after 7 seconds -->
        <script>
            setTimeout(function() {
                window.location.href = 'barangay_official_dashboard.php'; // Redirect to the Barangay Official Dashboard
            }, 7000); // 7-second delay before redirecting
        </script>
    <?php endif; ?>

    <!-- Back to Dashboard Button -->
    <div class="back-button-container">
        <a href="barangay_official_dashboard.php" class="button">Back to Dashboard</a>
    </div>
</div>

<script>
    let questionCount = 1;  // Initialize question count

    // Function to add more questions dynamically
    function addQuestion() {
        questionCount++;
        let container = document.getElementById('questions-container');
        let questionDiv = document.createElement('div');
        questionDiv.classList.add('form-group');
        questionDiv.id = `question-${questionCount}`;
        questionDiv.innerHTML = `<label for="question">Question ${questionCount}:</label><input type="text" name="questions[]" required><button type="button" class="remove-question" onclick="removeQuestion(${questionCount})">Remove</button>`;
        container.appendChild(questionDiv);
    }

    // Function to remove a question dynamically
    function removeQuestion(questionId) {
        const questionDiv = document.getElementById(`question-${questionId}`);
        questionDiv.remove();

        // Re-number the remaining questions
        let questions = document.querySelectorAll('#questions-container .form-group');
        let index = 1;
        questions.forEach(function(question) {
            question.querySelector('label').innerText = `Question ${index}:`;
            index++;
        });

        // Update questionCount to reflect the correct number of questions
        questionCount = index - 1;
    }
</script>

</body>
</html>
