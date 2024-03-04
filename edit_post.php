<!-- edit_post.php -->
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" type="text/css" href="blog.css"> 
</head>
<body>
    <?php
    // Database configuration (Replace with your actual credentials)
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

    // Check if the post ID is provided in the URL
    if (isset($_GET['id'])) {
        $postID = $_GET['id'];

        // Fetch the post details from the database based on the post ID
        $query = "SELECT * FROM blog WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $post = $result->fetch_assoc();
        } else {
            echo "Post not found.";
            // Optionally, add a link to go back to the main page if the post is not found
            // Example: echo "<a href='index.php'>Go Back to Main Page</a>";
            exit;
        }
    } else {
        echo "Post ID not provided.";
        // Optionally, add a link to go back to the main page or display an error message
        // Example: echo "<a href='index.php'>Go Back to Main Page</a>";
        exit;
    }

    // Handle form submission to update the blog post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $editImageName = null;

        // Validate input
        if (empty($title) || empty($content)) {
            die("Title and content are required fields.");
        }
        // Check if an image was uploaded
        if (isset($_FILES['editImage']) && $_FILES['editImage']['size'] > 0) {
            $editImage = $_FILES['editImage'];
            $editImageName = uniqid() . '_' . $editImage['name'];
            $editImageTmpName = $editImage['tmp_name'];

            // Move the uploaded image to the 'uploads' folder on your server
            $uploadsDir = 'uploads/';
            move_uploaded_file($editImageTmpName, $uploadsDir . $editImageName);

            // Delete the old image file if it exists and not null
            if (!empty($post['image'])) {
                $oldImagePath = $uploadsDir . $post['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        } else {
            // If no new image was uploaded, retain the existing image
            $editImageName = $post['image'];
        }
        // Check if the "Remove Current Image" checkbox is selected
        if (isset($_POST['removeImage']) && $_POST['removeImage'] === '1') {
            // Delete the old image file and set the image value to null
            if (!empty($post['image'])) {
                $oldImagePath = $uploadsDir . $post['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $editImageName = null;
            }
        }



        // Update the post in the database
        $query = "UPDATE blog SET title=?, content=?, image=?,timestamp=NOW() WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $title, $content,$editImageName,$postID);
        $result = $stmt->execute();

        if ($result) {
            // Redirect to the main page after successful update
            header("Location: blog.php");
            exit;
        } else {
            echo "Error updating post: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
    ?>

    <h1>Edit Post</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo $post['title']; ?>" required><br>

        <label for="content">Content:</label><br>
        <textarea name="content" rows="4" cols="50" required><?php echo $post['content']; ?></textarea><br>

        <label for="editImage">Update Image:</label>
        <input type="file" name="editImage"><br>
        <?php if (!empty($post['image'])) : ?>
        <label for="removeImage">Remove Current Image:</label>
        <input type="checkbox" name="removeImage" value="1"><br>
        <img src="uploads/<?php echo $post['image']; ?>" alt="Current Post Image"><br>
        <?php endif; ?>

        <input type="submit" value="Update Post">
    </form>
</body>
</html>
