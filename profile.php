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

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true && isset($_SESSION['username'])) {
    $email = $_SESSION['username'];

    // Retrieve the number of posts for the user from the blog table
    $query = "SELECT COUNT(*) AS post_count FROM blog WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $postCount = $user['post_count'];
    } else {
        // If user not found, handle the case accordingly (e.g., show an error message)
        echo "User not found.";
        exit;
    }
} else {
    // If the user is not logged in or no username in session, redirect to login page
    header("Location: login.php");
    exit;
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-image: url("image1.png");
            height: 700px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        h1 {
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        strong {
            font-weight: bold;
        }
        .container{
            
            background-color: beige;
            
            height: 230px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
        
        }
        .con{
            background: antiquewhite;
            margin:20px ;
            padding:10px;
            

        }

        /* Styling for the user profile section */
        .user-profile {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        /* Styling for the user profile title */
        .user-profile h1 {
            color: #007bff;
        }

        /* Styling for user details (email, post count) */
        .user-details {
            margin-top: 15px;
        }

        /* Styling for user details label */
        .user-details strong {
            width: 120px;
            display: inline-block;
        }

        /* Styling for user details value */
        .user-details p {
            margin-left: 130px;
        }
        nav {
        background-color: #333; /* Set background color */
        color: white; /* Set text color */
        padding: 10px; /* Add padding to the navigation bar */
        }
        #navi{
        display: flex;
        justify-content: end;
        }

        ul {
        list-style-type: none; /* Remove default list styling */
        margin: 0;
        padding: 0;
        }

        li {
        display: inline; /* Display list items horizontally */
        margin-right: 20px; /* Add some spacing between items */
        }

        a {
        text-decoration: none; /* Remove underline from links */
        color: white; /* Set link text color */
        }

        a:hover {
        color: #ccc; /* Change link text color on hover */
        }

    </style>
</head>
<body>
<nav id="navi">
        <ul>
            <li><a href="allpost.php">All Posts</a></li>
            <li><a href="blog.php">Your Posts</a></li>
            <li><a href="profile.php">Profile</a></li>
        </ul>
    </nav>
    <div class="container">
        <div class="con">
            <h1>User Profile</h1>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Number of Posts Published:</strong> <?php echo $postCount; ?></p>
        </div>        
    </div>   
</body>
</html>
