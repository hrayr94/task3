<?php
require_once 'upload.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']['name'])) {
    $uploadResult = uploadAndResizeImage($_FILES["image"]);

    if (is_array($uploadResult) && isset($uploadResult['original'], $uploadResult['thumbnail'])) {
        $message = "
            <h2>Original Image</h2>
            <img src='{$uploadResult['original']}' alt='Original Image'><br><br>
            <h2>Thumbnail</h2>
            <img src='{$uploadResult['thumbnail']}' alt='Thumbnail'><br>
        ";
    } else {
        $message = "<p>{$uploadResult}</p>";
    }
}
?>

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

<?= $message ?>
</body>
</html>
