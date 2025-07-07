<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login_register.css">
</head>
<body>

<div class="register-container">
    <h2>Login</h2>

    <form action="login_process.php" method="POST">
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="signup_page.php">Sign Up</a></p>

    <!-- Back to Home Button -->
    <a href="index.php" class="button">Back to Home</a>
</div>

</body>
</html>
