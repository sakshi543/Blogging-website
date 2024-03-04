<?php
// authenticate.php

session_start();

// Database configuration (Replace with your actual credentials)
$host = 'localhost';
$dbName = 'user_info';
$username = 'root';
$password = '';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the submitted username and password
$username = $_POST['username'];
$password = $_POST['password'];

// Validate input
if (empty($username) || empty($password)) {
    die("Username and password are required fields.");
}

// Fetch the user from the database based on the submitted username
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Verify the submitted password against the stored hashed password
    if (password_verify($password, $user['password'])) {
        // Authentication successful, set a session variable to indicate that the user is logged in
        $_SESSION['authenticated'] = true;
        // Store the username in the session
        $_SESSION['username'] = $user['username'];
        // Redirect the user to the main page (blog.php)
        header("Location: blog.php");
        exit;
    }
    else {
        echo "Invalid password. Please try again.";
    }
}

// If authentication fails, redirect the user back to the login page
header("Location: login.php");
exit;
