<?php
function uploadAndResizeImage($file) {
    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($file['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($file['tmp_name']);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        return 'File is not an image.';
    }

    if ($file['size'] > 500000) {
        return 'Sorry, your file is too large.';
    }

    if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg'
        && $imageFileType != 'gif' ) {
        return 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
    }

    if ($uploadOk == 0) {
        return 'Sorry, your file was not uploaded.';
    } else {
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
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
            $image = imagecreatefromjpeg($target_file);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width_orig, $height_orig);

            $thumbnail_filename = $target_dir . 'thumbnail_' . basename($file['name']);
            imagejpeg($image_p, $thumbnail_filename);

            ob_start();
            echo "<h2>Original Image</h2>";
            echo "<img src='$target_file' alt='Original Image'><br><br>";
            echo "<h2>Thumbnail</h2>";
            echo "<img src='$thumbnail_filename' alt='Thumbnail'><br>";
            $output = ob_get_clean();

            imagedestroy($image_p);
            return $output;
        } else {
            return 'Sorry, there was an error uploading your file.';
        }
    }
}
