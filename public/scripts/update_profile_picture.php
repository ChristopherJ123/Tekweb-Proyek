<?php
session_start();
include "../../src/db.php";
global $conn;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_picture'];

    // Validate the file
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array(strtolower($file_extension), $allowed_extensions)) {
        $_SESSION['errors'] = ["Invalid file type. Please upload a JPG, JPEG, PNG, or GIF file."];
        header("Location: ../profile.php");
        exit();
    }

    if ($file['size'] > 2 * 1024 * 1024) { // 2 MB limit
        $_SESSION['errors'] = ["File size exceeds 2MB limit."];
        header("Location: profile.php");
        exit();
    }

    // Generate unique file name
    $new_file_name = uniqid() . '.' . $file_extension;
    $upload_dir = "../uploads/profile_pictures/";
    $upload_path = $upload_dir . $new_file_name;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Move file to server
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_file_name, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile picture updated successfully.";
        } else {
            $_SESSION['errors'] = ["Failed to update profile picture in the database."];
        }

        $stmt->close();
    } else {
        $_SESSION['errors'] = ["Failed to upload file. Please try again."];
    }
}

header("Location: ../profile.php");
exit();
?>