<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload and Resize</title>
</head>
<body>
<h2>Upload an Image</h2>
<form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*">
    <button type="submit" name="submit">Upload Image</button>
</form>

<?php
require_once 'upload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']['name'])) {
    $uploadResult = uploadAndResizeImage($_FILES['image']);

    echo "<h3>Upload Result:</h3>";
    echo $uploadResult;
}

?>
</body>
</html>
