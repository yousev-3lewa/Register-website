<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $_SESSION['message'] = "Connection failed: " . $conn->connect_error;
    $_SESSION['message_type'] = "error";
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $second_name = !empty($_POST['second_name']) ? $_POST['second_name'] : NULL;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (empty($first_name) || empty($username) || empty($email) || empty($password)) {
        $_SESSION['message'] = "First Name, Username, Email, and Password are required.";
        $_SESSION['message_type'] = "error";
        header("Location: index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Email already registered.";
        $_SESSION['message_type'] = "error";
        header("Location: index.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (first_name, second_name, username, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $second_name, $username, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful! Please login.";
        $_SESSION['message_type'] = "success";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "error";
        header("Location: index.php");
        exit();
    }
    $stmt->close();
}

$conn->close();
?>