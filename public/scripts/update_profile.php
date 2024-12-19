<?php
session_start();
include "../../src/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $conn;

    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $bio = trim($_POST['bio']);
    $no_telp = trim($_POST['no_telp']);
    $profile_picture_file = $_FILES['profile_picture'];

    // Validate input
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (!filter_var($no_telp, FILTER_SANITIZE_NUMBER_INT)) {
        $errors[] = "Phone number must be numeric.";
    }

    // Handle file upload
    $uploaded_url = null;
    if (!empty($profile_picture_file['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($profile_picture_file['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        } elseif ($profile_picture_file['size'] > 2 * 1024 * 1024) { // 2MB limit
            $errors[] = "File size exceeds 2MB limit.";
        } else {
            // Generate unique file name and move the file
            $new_file_name = uniqid() . '.' . $file_extension;
            $upload_dir = "../../uploads/profile_pictures/";
            $upload_path = $upload_dir . $new_file_name;

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (move_uploaded_file($profile_picture_file['tmp_name'], $upload_path)) {
                // Convert file path to a URL
                $uploaded_url = "https://pasarkakilima.guraa.me/uploads/profile_pictures/" . $new_file_name;
            } else {
                $errors[] = "Failed to upload file. Please try again.";
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../profile.php");
        exit();
    }

    // Update user profile
    $query = "UPDATE users SET username = ?, bio = ?, no_telp = ?, profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $username, $bio, $no_telp, $uploaded_url, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully.";
    } else {
        $_SESSION['errors'] = ["Failed to update profile. Please try again."];
    }

    $stmt->close();
    header("Location: ../profile.php");
    exit();
}
?>
