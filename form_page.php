<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Document Submission</title>
    <link rel="stylesheet" href="css/front_page.css">
</head>
<body>

<div class="container">
    <h2>Barangay Document Submission Form</h2>

    <form method="POST" action="submit_form.php" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
        </div>

        <div class="form-group">
            <label for="document">Upload Document (ID, Certificate, etc.):</label>
            <input type="file" name="document" id="document" required>
        </div>

        <div class="form-group">
            <input type="submit" value="Submit">
        </div>
    </form>
</div>

</body>
</html>
