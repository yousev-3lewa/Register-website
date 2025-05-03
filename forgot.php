<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $retype_password = $_POST['retype_password'] ?? '';

    // Validate inputs
    if (empty($email) || empty($new_password) || empty($retype_password)) {
        $message = "All fields are required.";
        $message_type = "error";
    } elseif ($new_password !== $retype_password) {
        $message = "Passwords do not match.";
        $message_type = "error";
    } elseif (strlen($new_password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                $message = "Password updated successfully!";
                $message_type = "success";
                header("Refresh: 2; URL=login.php");
            } else {
                $message = "Error updating password: " . $stmt->error;
                $message_type = "error";
            }
        } else {
            $message = "Email not found.";
            $message_type = "error";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if (!empty($message)) { ?>
            <p style="color: <?php echo $message_type === 'error' ? 'red' : 'green'; ?>; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>
        <form action="forgot.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="retype_password">Retype New Password</label>
                <input type="password" id="retype_password" name="retype_password" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">
            <a href="login.php">Back to Login</a>
        </p>
    </div>
</body>
</html>
</xArtifact>
