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
$total = 0; // Initialize total for cart
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Fetch addresses associated with the user
$queryAddress = "SELECT * FROM address WHERE user_id = ?";
$stmtAddress = mysqli_prepare($conn, $queryAddress);
if ($stmtAddress) {
    mysqli_stmt_bind_param($stmtAddress, 'i', $user_id);
    mysqli_stmt_execute($stmtAddress);
    $resultAddress = mysqli_stmt_get_result($stmtAddress);

    $addresses = [];
    if ($resultAddress && mysqli_num_rows($resultAddress) > 0) {
        while ($row = mysqli_fetch_assoc($resultAddress)) {
            $addresses[] = $row;
        }
    }
    mysqli_stmt_close($stmtAddress);
} else {
    $addresses = [];
}

// Fetch cart items from session
$cartItems = [];
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productID => $amount) {
        $queryProduct = "SELECT name, image_link, price FROM products WHERE id = ?";
        $stmtProduct = mysqli_prepare($conn, $queryProduct);
        if ($stmtProduct) {
            mysqli_stmt_bind_param($stmtProduct, 'i', $productID);
            mysqli_stmt_execute($stmtProduct);
            $resultProduct = mysqli_stmt_get_result($stmtProduct);
            $product = mysqli_fetch_assoc($resultProduct);
            if ($product) {
                $cartItems[] = array_merge($product, ['amount' => $amount]);
                $total += $product['price'] * $amount;
            }
            mysqli_stmt_close($stmtProduct);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasar Kaki Lima | Checkout</title>
    <link rel="stylesheet" href="../public/styles.css">
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
            padding: 0px;
        }
        .checkout-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .checkout-header {
            text-align: center;
            padding: 20px;
            background: #ff9f43;
            color: white;
        }
        .checkout-header h1 {
            font-size: 24px;
        }
        .address-section, .add-address-btn {
            padding: 20px;
        }
        .address-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .address-card p {
            margin: 5px 0;
            font-size: 14px;
        }
        .address-card strong {
            font-size: 16px;
            color: #ff9f43;
        }
        .add-address-btn a {
            display: block;
            text-align: center;
            background: #ff9f43;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }
        .add-address-btn a:hover {
            background: #e68a33;
        }
        .cart-items {
            padding: 20px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            margin-right: 10px;
        }
        .cart-item-details {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Checkout</h1>
        </div>
       
        <div class="cart-items">
            <?php if (empty($cartItems)) { ?>
                <p>Your cart is empty.</p>
            <?php } else { 
                foreach ($cartItems as $item) { ?>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($item['image_link']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="cart-item-details">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p>Price: Rp<?= number_format($item['price'], 0, ',', '.') ?> x <?= $item['amount'] ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="address-section">
            <?php if (empty($addresses)) { ?>
                <div class="text-center">
                    <p class="text-lg mb-4">Tidak ada alamat tersimpan.</p>
                    <div class="add-address-btn">
                        <a href="add_address.php">Add Address</a>
                    </div>
                </div>
            <?php } else { 
                foreach ($addresses as $address) { ?>
                    <div class="address-card">
                        <p><strong>Nama:</strong> <?= htmlspecialchars($address['full_name']) ?></p>
                        <p><strong>Alamat:</strong> <?= htmlspecialchars($address['alamat']) ?></p>
                        <p><strong>Kecamatan & Kota:</strong> <?= htmlspecialchars($address['kecamatan']) ?>, <?= htmlspecialchars($address['kota']) ?></p>
                        <p><strong>Provinsi & Kode Pos:</strong> <?= htmlspecialchars($address['provinsi']) ?>, <?= htmlspecialchars($address['kode_pos']) ?></p>
                        <p><strong>Notes:</strong> <?= htmlspecialchars($address['catatan']) ?></p>
                    </div>
                <?php } ?>
                <div class="add-address-btn">
                    <a href="add_address.php">Add Address</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
