<?php
session_start();
include '../src/db.php';
global $conn;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['errors'] = ['You need to log in to access this page.'];
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the address ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['errors'] = ['Address ID is missing.'];
    header('Location: ../public/checkout.php');
    exit();
}

$address_id = $_GET['id'];

// Fetch the address data
$query = "SELECT * FROM address WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ii', $address_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $address = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$address) {
        $_SESSION['errors'] = ['Address not found.'];
        header('Location: ../public/checkout.php');
        exit();
    }
} else {
    $_SESSION['errors'] = ['Failed to fetch address data.'];
    header('Location: ../public/checkout.php');
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $alamat = trim($_POST['alamat']);
    $provinsi = trim($_POST['provinsi']);
    $kota = trim($_POST['kota']);
    $kecamatan = trim($_POST['kecamatan']);
    $kode_pos = trim($_POST['kode_pos']);
    $catatan = isset($_POST['catatan']) && trim($_POST['catatan']) !== '' ? trim($_POST['catatan']) : null;

    $errors = [];

    // Validate required fields
    if (empty($full_name)) $errors[] = 'Full Name is required.';
    if (empty($alamat)) $errors[] = 'Address is required.';
    if (empty($provinsi)) $errors[] = 'Province is required.';
    if (empty($kota)) $errors[] = 'City is required.';
    if (empty($kecamatan)) $errors[] = 'Subdistrict is required.';
    if (empty($kode_pos) || !ctype_digit($kode_pos)) $errors[] = 'Postal Code must be numeric.';

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: edit_address_page.php?id=' . $address_id);
        exit;
    }

    $updateQuery = "UPDATE address SET full_name = ?, alamat = ?, provinsi = ?, kota = ?, kecamatan = ?, kode_pos = ?, catatan = ? WHERE id = ? AND user_id = ?";
    $stmtUpdate = mysqli_prepare($conn, $updateQuery);
    if ($stmtUpdate) {
        mysqli_stmt_bind_param($stmtUpdate, 'ssssssiii', $full_name, $alamat, $provinsi, $kota, $kecamatan, $kode_pos, $catatan, $address_id, $user_id);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);

        $_SESSION['success'] = 'Address updated successfully.';
        header('Location: ../public/checkout.php');
        exit();
    } else {
        $_SESSION['errors'] = ['Failed to update address.'];
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Address</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .header {
            text-align: center;
            background: #ff9f43;
            color: #fff;
            padding: 10px 0;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            font-size: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }
        label {
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #ff9f43;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #e68a33;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Address</h1>
        </div>
        <form action="" method="POST">
            <div>
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($address['full_name']) ?>" required>
            </div>
            <div>
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($address['alamat']) ?></textarea>
            </div>
            <div>
                <label for="provinsi">Provinsi</label>
                <input type="text" id="provinsi" name="provinsi" value="<?= htmlspecialchars($address['provinsi']) ?>" required>
            </div>
            <div>
                <label for="kota">Kota</label>
                <input type="text" id="kota" name="kota" value="<?= htmlspecialchars($address['kota']) ?>" required>
            </div>
            <div>
                <label for="kecamatan">Kecamatan</label>
                <input type="text" id="kecamatan" name="kecamatan" value="<?= htmlspecialchars($address['kecamatan']) ?>" required>
            </div>
            <div>
                <label for="kode_pos">Kode Pos</label>
                <input type="text" id="kode_pos" name="kode_pos" value="<?= htmlspecialchars($address['kode_pos']) ?>" required>
            </div>
            <div>
                <label for="catatan">Catatan (Optional)</label>
                <textarea id="catatan" name="catatan" rows="2"><?= htmlspecialchars($address['catatan']) ?></textarea>
            </div>
            <button type="submit">Update Address</button>
        </form>
    </div>
</body>
</html>
