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

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $postID = $_GET['id'];

    // Prepare and execute the SQL query to retrieve the post details
    $query = "SELECT * FROM blog WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $post = $result->fetch_assoc();
        $title = $post['title'];
        $content = $post['content'];
        $timestamp = $post['timestamp'];
        $image = $post['image'];
    } else {
        // If post not found, handle the case accordingly (e.g., show an error message)
        echo "Post not found.";
        exit;
    }
} else {
    // If the 'id' parameter is not present, redirect to the index page
    header("Location: index.php");
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
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        img {
            max-width: 100%;
            max-height: 300px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        p {
            margin-bottom: 10px;
        }

        /* Style for the "Read More" link */
        .read-more {
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        .read-more a {
            color: #007bff;
            text-decoration: none;
        }

        .read-more a:hover {
            text-decoration: underline;
        }
        .container{
            margin-top: 40px;
            background-color: beige;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
        }
        .con{
            background-color: aliceblue;
            padding: 20px;
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

        #navi a {
        text-decoration: none; /* Remove underline from links */
        color: white; /* Set link text color */
        }
        .gr{
            display: grid;
            grid-template-rows: repeat(2, 1fr);
        }

        </style>
</head>
<body>
    <div class="gr">
    <div>
        <nav id="navi">
            <ul>
                <li><a href="allpost.php">All Posts</a></li>
                <li><a href="blog.php">Your Posts</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </div>
    <div class="bod">
        <div class="container">
            <div class="con">
                <h1><?php echo $title; ?></h1>
                <p>Published on: <?php echo $timestamp; ?></p>
                <?php
                // Display the image if available
                if (!empty($image)) {
                    echo "<img src='uploads/$image' alt='Post Image'>";
                }
                ?>
                <p><?php echo nl2br($content); ?></p>
            </div>
        </div>
    </div>
    
    
    </div>
    
    
</body>
</html>
