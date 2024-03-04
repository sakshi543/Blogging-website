<?php
// delete_post.php

// Database configuration
$host = 'localhost';
$dbName = 'blog_content';
$username = 'root';
$password = '';

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle deletion of the post
if (isset($_GET['id'])) {
    $postID = $_GET['id'];
    $query = "DELETE FROM blog WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postID);
    $result = $stmt->execute();

    if ($result) {
        header("Location: blog.php"); // Redirect to main page after successful deletion
        exit;
    } else {
        echo "Error deleting post: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
