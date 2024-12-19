<?php
session_start();
include "../../src/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_picture'];

    // Validate the file
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['errors'] = ["Invalid file type. Only JPG, JPEG, PNG, or GIF allowed."];
        header("Location: ../profile.php");
        exit();
    }

    if ($file['size'] > 2 * 1024 * 1024) { // 2 MB limit
        $_SESSION['errors'] = ["File size exceeds 2MB limit."];
        header("Location: ../profile.php");
        exit();
    }

    // Create upload directory if it doesn't exist
    $upload_dir = "../uploads/profile_pictures/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Generate unique file name
    $new_file_name = uniqid() . '.' . $file_extension;
    $upload_path = $upload_dir . $new_file_name;

    // Move the uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Save the relative path to the database
        $relative_path = "uploads/profile_pictures/" . $new_file_name;

        $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $relative_path, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile picture updated successfully.";
        } else {
            $_SESSION['errors'] = ["Failed to update database."];
        }

        $stmt->close();
    } else {
        $_SESSION['errors'] = ["Failed to upload file."];
    }
}

header("Location: ../profile.php");
exit();
?>
