
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viSewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <link rel="stylesheet" type="text/css" href="blog.css"> 
</head>
<body>
<nav id="navi">
        <ul>
            <li><a href="allpost.php">All Posts</a></li>
            <li><a href="blog.php">Your Posts</a></li>
            <li><a href="profile.php">Profile</a></li>
        </ul>
    </nav>
    <button id="addPostButton">Add Post</button>
    <br>
    <div id="postForm" style="display:none;">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" required><br>

            <label for="content">Content:</label><br>
            <textarea name="content" rows="4" cols="50" required></textarea><br>

            <label for="image">Image:</label>
            <input type="file" name="image"><br>

            <input type="submit" value="Create Post">
        </form>
    </div>

    <h1>Your Blog Posts:</h1>
    <br>
    <br>
    <div id="mainContent">
    <?php
    session_start();
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

    // Check if the user is logged in and the username is stored in the session
    if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true && isset($_SESSION['username'])) {
      $username = $_SESSION['username'];

    // Handle form submission to save a new blog post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image_name = null; 
        $username = $_SESSION['username'];

        // Validate input
        if (empty($title) || empty($content)) {
            die("Title and content are required fields.");
        }
        
        // Check if an image was uploaded
         if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $image = $_FILES['image'];
            $image_name = uniqid() . '_' . $image['name'];
            $image_tmp_name = $image['tmp_name'];

            // Move the uploaded image to the 'uploads' folder on your server
           $uploads_dir = 'uploads/';
           move_uploaded_file($image_tmp_name, $uploads_dir . $image_name);
        }
        else {
          $image_name = null; // Set image_name to null when no image is uploaded
        }



        $query = "INSERT INTO blog (title, content, image, timestamp,username) VALUES (?, ?, ?, NOW(),?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $title, $content, $image_name,$username);


        $result = $stmt->execute();

        if ($result) {
            // Redirect to prevent form resubmission on page refresh
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error creating post: " . $conn->error;
        }
    }
    

        // Display only the posts associated with the logged-in user's username
        $query = "SELECT * FROM blog WHERE username = ? ORDER BY timestamp DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        // Fetch the data from the result object
        $posts = [];
          while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
          }
        }
       else {
        // If the user is not logged in or no username in session, redirect to login page
        header("Location: login.php");
        exit;
      }

      // Display existing posts with Edit and Delete buttons
      displayPosts($posts);

    // Close the database connection
    $conn->close();
        function displayPosts($posts) {
          if (empty($posts)) {
              echo "<p>No posts found.</p>";
              return;
          }
          foreach ($posts as $post) {
              $postID = $post['id'];
              $title = $post['title'];
              $content = $post['content'];
              $timestamp = $post['timestamp'];
              $image = $post['image'];

              // Split the content into an array of lines
              $lines = explode("\n", $content);
              // Get the first 5 lines of the content
              $summary = implode("\n", array_slice($lines, 0, 5));
              echo "<article>";
              echo "<h3>Title: $title</h3>";
              echo "<div>$summary</div>"; // Display the summary in a div
              // Add a "Read More" link to view_full_post.php
              echo "<a href='viewpost.php?id=$postID'>Read More</a>";
              echo "<p>Timestamp: $timestamp</p>";
      
              // Display the image if available
              if (!empty($image)) {
                  echo "<img src='uploads/$image' alt='Post Image'>";
              }
      
              // Check if $postID is defined before displaying the Edit and Delete links
              if (isset($postID)) {
                  echo "<a href='edit_post.php?id=$postID'><button>Edit</button></a> ";
                  echo "<a href='delete_post.php?id=$postID'><button>Delete</button></a>";
              }
      
              echo "</article>";
          }
        }
  
    ?>
    </div>

    <script src="blog.js"></script>
    <script>
        const addPostButton = document.getElementById("addPostButton");
        const postForm = document.getElementById("postForm");

        addPostButton.addEventListener("click", function() {
            if (postForm.style.display === "none") {
                postForm.style.display = "block";
            } else {
                postForm.style.display = "none";
            }
        });
    </script>
</body>
</html>
