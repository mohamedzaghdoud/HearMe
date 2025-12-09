<?php

require_once __DIR__ . "/../../Controller/postController.php";
require_once __DIR__ . "/../../Model/post.php";

// Controller
$postC = new postController();

// Get post ID from GET
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Post ID is missing!");
}

// Fetch post object
$post = $postC->showpost($id);
if (!$post) {
    die("Post not found in database!");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Show Post</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        img { max-width: 150px; display: block; margin-top: 10px; }
    </style>
</head>
<body>

<h1 style="text-align:center;">Post Details</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Content</th>
        <th>Image</th>
        <th>Likes</th>
        <th>Date</th>
    </tr>

    <tr>
        <td><?php echo htmlspecialchars($post->getId()); ?></td>

        <td><?php echo nl2br(htmlspecialchars($post->getContent())); ?></td>

        <td>
            <?php 
                if ($post->getImage()) {
                    echo '<img src="../../uploads/' . htmlspecialchars($post->getImage()) . '" alt="Post Image">';
                } else {
                    echo "No image";
                }
            ?>
        </td>

        <td><?php echo htmlspecialchars($post->getLikes()); ?></td>

        <td>
            <?php 
                echo htmlspecialchars(
                    $post->getCreated_at()->format("Y-m-d H:i:s")
                ); 
            ?>
        </td>
    </tr>
</table>

<div style="text-align:center;">
    <a href="addpost.php">← Back to Posts</a>
</div>

</body>
</html>
