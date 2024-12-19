<?php
session_start();
include "../../src/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $bio = trim($_POST['bio']);
    $no_telp = trim($_POST['no_telp']);

    // Validate input
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (!filter_var($no_telp, FILTER_SANITIZE_NUMBER_INT)) {
        $errors[] = "Phone number must be numeric.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../profile.php");
        exit();
    }

    // Update user profile
    $query = "UPDATE users SET username = ?, bio = ?, no_telp = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $username, $bio, $no_telp, $user_id);

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
