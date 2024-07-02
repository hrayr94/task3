<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"]["name"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";

            // Resize image
            $thumbnail_width = 150;
            $thumbnail_height = 150;

            // Get new dimensions
            list($width_orig, $height_orig) = getimagesize($target_file);
            $ratio_orig = $width_orig / $height_orig;

            if ($thumbnail_width / $thumbnail_height > $ratio_orig) {
                $thumbnail_width = $thumbnail_height * $ratio_orig;
            } else {
                $thumbnail_height = $thumbnail_width / $ratio_orig;
            }

            $image_p = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
            $image = imagecreatefromjpeg($target_file); // Use imagecreatefrompng() if PNG
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width_orig, $height_orig);

            $thumbnail_filename = $target_dir . "thumbnail_" . basename($_FILES["image"]["name"]);
            imagejpeg($image_p, $thumbnail_filename); // Use imagepng() for PNG

            echo "<h2>Original Image</h2>";
            echo "<img src='$target_file' alt='Original Image'><br><br>";
            echo "<h2>Thumbnail</h2>";
            echo "<img src='$thumbnail_filename' alt='Thumbnail'>";

            imagedestroy($image_p);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
