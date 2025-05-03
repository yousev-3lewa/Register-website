<?php
session_start();
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
unset($_SESSION['message'], $_SESSION['message_type']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (!empty($message)) { ?>
            <p style="color: <?php echo $message_type === 'error' ? 'red' : 'green'; ?>; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
            <?php if ($message_type === 'success') { ?>
                <script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000); // Redirect to login.php after 2 seconds
                </script>
            <?php } ?>
        <?php } ?>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="second_name">Second Name</label>
                <input type="text" id="second_name" name="second_name">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>