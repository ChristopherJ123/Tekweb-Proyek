<?php
session_start();
include '../../src/db.php';
global $conn;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['errors'] = ['You need to log in to access this page.'];
    header('Location: ../login.php');
    exit();
}

// Get the address ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $address_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Verify that the address belongs to the logged-in user
    $query = "DELETE FROM address WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $address_id, $user_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['success'] = 'Address deleted successfully.';
        } else {
            $_SESSION['errors'] = ['Failed to delete address.'];
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['errors'] = ['Database query failed.'];
    }
} else {
    $_SESSION['errors'] = ['Invalid address ID.'];
}

// Redirect back to the checkout page
header('Location: ../checkout.php');
exit();
?>
