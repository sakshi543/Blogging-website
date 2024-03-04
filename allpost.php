<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <link rel="stylesheet" type="text/css" href="blog.css"> 
</head>
<body>
<nav id="navi">
        <ul>
            <li><a href="allpost.php">All Posts</a></li>
            <li><a href="blog.php">Your Posts</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="login.php">LogOut</a> </li>
        </ul>
    </nav>
    <br>
    <br>
    <div id="mainContent">
        <?php
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

        // Fetch all posts
        $query = "SELECT id, title, content, timestamp, image, username 
                  FROM blog
                  ORDER BY timestamp DESC";

        $result = $conn->query($query);

        if (!$result) {
            die("Error retrieving posts: " . $conn->error);
        }

        // Check if any posts are available
        if ($result->num_rows === 0) {
            echo "<p>No posts found.</p>";
        } else {
            // Loop through all posts and display them
            while ($row = $result->fetch_assoc()) {
                $postID = $row['id'];
                $title = $row['title'];
                $content = $row['content'];
                $timestamp = $row['timestamp'];
                $image = $row['image'];
                $author = $row['username'];

                echo "<article>";
                echo "<h3>Title: $title</h3>";
                echo "<p>Author: $author</p>";
                // Display only the first 5 lines of the content
                $contentLines = explode("\n", $content);
                $excerpt = implode("\n", array_slice($contentLines, 0, 5));
                echo "<p>Content: $excerpt</p>";
                echo "<p>Timestamp: $timestamp</p>";

                // Display the image if available
                if (!empty($image)) {
                    echo "<img src='uploads/$image' alt='Post Image'>";
                }
                // Check if $postID is defined before displaying the "Read More" link
                if (isset($postID)) {
                    echo "<a href='viewpost.php?id=$postID'>Read More</a>";
                }
    
                echo "</article>";
            }
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

</body>
</html>
