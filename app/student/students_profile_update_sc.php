<?php // Adjust the path as needed

if (isset($_POST['update_profile'])) {
    $about = conn()->sanitize($_POST['about']);
    $address = conn()->sanitize($_POST['address']);

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $image_name = $_FILES['profile_image']['name'];
        $image_tmp_name = $_FILES['profile_image']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $folderDirectory = "/uploads/";

        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if (in_array($image_ext, $allowed_extensions)) {
            $new_image_name = uniqid() . '.' . $image_ext;
            // $image_upload = dirname(__DIR__) . DIRECTORY_SEPARATOR . $new_image_name;
            $image_upload_path = "uploads/$new_image_name";

            // Ensure the uploads directory exists and is writable
            // if (!is_dir('uploads')) {
            //     mkdir('uploads', 0777, true);
            // }

            // if (!move_uploaded_file($image_tmp_name, $image_upload)) {
            //     echo "<script>alert('Failed to move uploaded file.');</script>";
            //     exit;
            // }
            
            try {
                $tmpFile = "{$image_tmp_name}.{$image_ext}";
                rename($image_tmp_name, $tmpFile);
                $respd = uploadToStorageApi($tmpFile, $_FILES['profile_image']['type'], $new_image_name, $folderDirectory);
            } catch (Exception $e) {
                echo "<script>alert('Failed to move uploaded file.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Invalid image format. Only JPG, JPEG, and PNG allowed.');</script>";
            exit;
        }
    }

    // Update the database
    $query = "UPDATE students SET about = ?, complete_address = ?";
    if ($image_upload_path ?? false) {
        $query .= ", profile_image = ?";
    }
    $query .= " WHERE id = ?";

    $stmt = conn()->prepare($query);
    $uid = user_id();
    if ($image_upload_path ?? false) {
        $stmt->bind_param("sssi", $about, $address, $image_upload_path, $uid);
    } else {
        $stmt->bind_param("ssi", $about, $address, $uid);
    }
    if ($stmt->execute()) {
        echo "<script>alert('Profile Successfully Updated!');
        document.location='students_profile';</script>";
    } else {
        echo "Error: " . mysqli_error(conn()->get_conn());
    }
}
