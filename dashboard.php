<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use first_name from session
$first_name = $_SESSION['first_name'] ?? 'Student'; // Fallback to 'Student' if not set

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subject']) && isset($_POST['score'])) {
    $student_id = $_SESSION['student_id'];
    $subject = $_POST['subject'];
    $score = (int)$_POST['score'];

    if (empty($subject) || $score < 0 || $score > 100) {
        $message = "Please enter a valid subject and score (0-100).";
    } else {
        if ($score >= 85) {
            $grade = 'A';
            $status = 'Excellent';
        } elseif ($score >= 75) {
            $grade = 'B';
            $status = 'Very Good';
        } elseif ($score >= 65) {
            $grade = 'C';
            $status = 'Good';
        } elseif ($score >= 55) {
            $grade = 'D';
            $status = 'Passed';
        } else {
            $grade = 'F';
            $status = 'Fail';
        }

        $stmt = $conn->prepare("INSERT INTO grades (student_id, subject, score, grade, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isiss", $student_id, $subject, $score, $grade, $status);

        if ($stmt->execute()) {
            $message = "Score uploaded successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle grade reset
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_grades'])) {
    $student_id = $_SESSION['student_id'];
    $stmt = $conn->prepare("DELETE FROM grades WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        $message = "All grades reset successfully!";
    } else {
        $message = "Error resetting grades: " . $stmt->error;
    }
    $stmt->close();
}

$student_id = $_SESSION['student_id'];
$stmt = $conn->prepare("SELECT subject, score, grade, status, uploaded_at FROM grades WHERE student_id = ? ORDER BY uploaded_at DESC");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h2>
        <p style="text-align: center; margin-bottom: 20px;">Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <form action="logout.php" method="POST" style="text-align: center; margin-bottom: 30px;">
            <button type="submit" style="background: #dc3545;">Log Out</button>
        </form>

        <h3>Upload Your Score</h3>
        <?php if (!empty($message)) { echo "<p style='color: " . (strpos($message, "Error") === false ? "green" : "red") . "; text-align: center;'>$message</p>"; } ?>
        <form action="dashboard.php" method="POST">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="score">Score (0-100)</label>
                <input type="number" id="score" name="score" min="0" max="100" required>
            </div>
            <button type="submit">Upload Score</button>
        </form>

        <h3 style="margin-top: 30px;">Your Scores</h3>
        <form action="dashboard.php" method="POST" style="text-align: center; margin-bottom: 20px;">
            <input type="hidden" name="reset_grades" value="1">
            <button type="submit" style="background: #ff9800;" onclick="return confirm('Are you sure you want to reset all your grades? This cannot be undone.');">Reset All Grades</button>
        </form>
        <?php if ($result->num_rows > 0) { ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <!-- Table header for grades -->
                <tr style="background: #f1f1f1;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Subject</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Score</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Grade</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Uploaded At</th>
                </tr>
                <!-- Table rows for grades -->
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['score']); ?></td>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['grade']); ?></td>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['status']); ?></td>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p style="text-align: center;">No scores uploaded yet.</p>
        <?php } ?>
    </div>
</body>
</html>