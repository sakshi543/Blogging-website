<?php
session_start();

$host = 'localhost';
$dbName = 'blog_content';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);

    $result = $stmt->execute();

    if ($result) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error creating user: " . $conn->error;
    }
}

$conn->close();
?>
