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

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['errors'] = ["File upload error. Please try again."];
        header("Location: ../../../../profile.php");
        exit();
    }

    // Validate file extension
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['errors'] = ["Invalid file type. Allowed types: JPG, JPEG, PNG, GIF."];
        header("Location: public/../../profile.php");
        exit();
    }

    // Validate file size (2MB limit)
    if ($file['size'] > 2 * 1024 * 1024) {
        $_SESSION['errors'] = ["File size exceeds 2MB limit."];
        header("Location: public/../../profile.php");
        exit();
    }

    // Generate unique file name and path
    $upload_dir = "../uploads/profile_pictures/";
    $new_file_name = uniqid("profile_", true) . '.' . $file_extension;
    $full_path = $upload_dir . $new_file_name;

    // Ensure upload directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $full_path)) {
        // Fetch current profile picture to delete old file
        $query = "SELECT profile_picture FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_data = $result->fetch_assoc();
        $stmt->close();

        // Delete old picture if not default or an external URL
        if ($current_data['profile_picture'] && !filter_var($current_data['profile_picture'], FILTER_VALIDATE_URL) &&
            $current_data['profile_picture'] !== 'default_profile.png') {
            $old_file_path = "../" . $current_data['profile_picture'];
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        }

        // Update new profile picture in the database
        $relative_path = "uploads/profile_pictures/" . $new_file_name; // Save relative path
        $update_query = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $relative_path, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile picture updated successfully.";
        } else {
            $_SESSION['errors'] = ["Failed to update profile picture in the database."];
        }
        $stmt->close();
    } else {
        $_SESSION['errors'] = ["Failed to move uploaded file. Please try again."];
    }
}

header("Location: public/../../profile.php");
exit();
