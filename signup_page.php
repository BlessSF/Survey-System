<?php
// Start session to check for success message
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/login_register.css">
</head>
<body>

<div class="register-container">
    <h2>Create Account</h2>
    <form action="register_process.php" method="POST">
        <!-- Form Fields -->
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" name="middle_name">
        </div>
        <div class="form-group">
            <label for="sex">Sex</label>
            <select name="sex" required>
                <option value="">--Select--</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Prefer not to say">Prefer not to say</option>
            </select>
        </div>
        <div class="form-group">
            <label for="civil_status">Civil Status</label>
            <select name="civil_status" required>
                <option value="">--Select--</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="mobile">Mobile Number</label>
            <input type="text" name="mobile" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <input type="submit" value="Register">
    </form>

    <p>Already have an account? <a href="login_page.php">Login here</a></p>
</div>

<!-- Floating Success Message -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success-message">
        <?php echo $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

</body>
</html>
