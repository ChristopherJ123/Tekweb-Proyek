<?php
session_start();
include '../../src/db.php'; // Database connection
global $conn;

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['errors'] = ['You must be logged in to add an address.'];
    header('Location: ../login.php'); // Redirect to login page
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; 
    $full_name = trim($_POST['full_name']);
    $alamat = trim($_POST['alamat']);
    $provinsi = trim($_POST['provinsi']);
    $kota = trim($_POST['kota']);
    $kecamatan = trim($_POST['kecamatan']);
    $kode_pos = trim($_POST['kode_pos']);
    $catatan = isset($_POST['catatan']) ? trim($_POST['catatan']) : null;

    $errors = [];

  
    if (empty($full_name)) $errors[] = 'Full Name is required.';
    if (empty($alamat)) $errors[] = 'Address is required.';
    if (empty($provinsi)) $errors[] = 'Province is required.';
    if (empty($kota)) $errors[] = 'City is required.';
    if (empty($kecamatan)) $errors[] = 'Subdistrict is required.';
    if (empty($kode_pos) || !ctype_digit($kode_pos)) $errors[] = 'Postal Code must be numeric.';

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: add_address_page.php'); // Redirect back to the form
        exit;
    }

   
    $query = "INSERT INTO address (user_id, full_name, alamat, provinsi, kota, kecamatan, kode_pos, catatan) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("isssssis", $user_id, $full_name, $alamat, $provinsi, $kota, $kecamatan, $kode_pos, $catatan);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Address added successfully!';
            header('Location: ../checkout.php'); // Redirect back to the form
            exit;
        } else {
            $_SESSION['errors'] = ['Failed to save address.'];
            header('Location: ../add_address_page.php'); // Redirect back with errors
            exit;
        }
    } else {
        $_SESSION['errors'] = ['Failed to prepare the statement.'];
        header('Location: ../add_address_page.php'); // Redirect back with errors
        exit;
    }
} else {
    // Redirect if accessed without POST
    header('Location: ../add_address_page.php');
    exit;
}
