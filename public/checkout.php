<?php
session_start();
include "../src/db.php";
global $conn;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout</title>
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
        .checkout-details {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group textarea {
            resize: none;
        }
        .total-section {
            text-align: right;
            padding: 20px;
            font-size: 18px;
            border-top: 2px solid #ff9f43;
        }
        .checkout-btn {
            display: block;
            width: 100%;
            text-align: center;
            background: #ff9f43;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background: #e68a33;
        }
        .add-address-btn {
            display: block;
            width: 100%;
            text-align: center;
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
        }
        .add-address-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Checkout</h1>
        </div>
        <div class="cart-items">
            <?php
            $total = 0;
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $productID => $amount) {
                    $queryProduct = "SELECT name, image_link, price FROM products WHERE id = '$productID'";
                    $product = mysqli_fetch_assoc(mysqli_query($conn, $queryProduct));
                    $total += $product['price'] * $amount;
                    ?>
                    <div class="cart-item">
                        <img src="<?=$product['image_link']?>" alt="<?=$product['name']?>">
                        <div class="cart-item-details">
                            <h3><?=$product['name']?></h3>
                            <p>Price: Rp<?=number_format($product['price'], 0, ',', '.')?> x <?=$amount?></p>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <p>Your cart is empty.</p>
            <?php } ?>
        </div>
        <div class="checkout-details">
            <form action="process_checkout.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="address">Shipping Address</label>
                    <select id="address" name="address" required>
                        <?php
                        $user_id = $_SESSION['user_id']; // Assume the user is logged in
                        $queryAddresses = "SELECT * FROM addresses WHERE user_id = '$user_id'";
                        $addresses = mysqli_query($conn, $queryAddresses);
                        while ($address = mysqli_fetch_assoc($addresses)) {
                            echo "<option value=\"" . $address['id'] . "\">" . $address['full_name'] . " - " . $address['alamat'] . ", " . $address['kecamatan'] . ", " . $address['kota'] . ", " . $address['provinsi'] . " - " . $address['kode_pos'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="new-address">Or Add a New Address</label>
                    <textarea id="new-address" name="new_address" rows="4" placeholder="Enter new address details..."></textarea>
                </div>
                <button type="button" class="add-address-btn" style="background: red;">Add Address</button>
                <div class="total-section">
                    <strong>Total: Rp<?=number_format($total, 0, ',', '.');?></strong>
                </div>
                <input type="hidden" name="total" value="<?=$total?>">
                <button type="submit" class="checkout-btn">Place Order</button>
            </form>
        </div>
    </div>
</body>
</html>
