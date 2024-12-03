<?php
session_start();
include '../../src/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$productID = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_INT);

if ($productID && $amount) {
    if (isset($_SESSION['cart'][$productID])) {
        if ($_SESSION['cart'][$productID] + $amount > 0) {
            $_SESSION['cart'][$productID] += $amount;
            updateCart();
        } else {
            echo json_encode(['success' => false, 'message' => 'Jumlah item tidak boleh negatif!']);
        }
    } else if ($amount > 0) {
        $_SESSION['cart'][$productID] = $amount;
        updateCart();
    } else {
        echo json_encode(['success' => false, 'message' => 'Jumlah yang ditambahkan tidak valid!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product atau jumlah yang ditambahkan tidak valid!']);
}

function updateCart(): void
{
    global $conn;
    $totalItems = array_sum($_SESSION['cart']);
    $totalPrice = 0;

    $cartItems = [];
    foreach ($_SESSION['cart'] as $productID => $amount) {
        $queryProduct = "SELECT name, image_link, price FROM products WHERE id = '$productID'";
        $result = mysqli_fetch_assoc(mysqli_query($conn, $queryProduct));
        $result['quantity'] = $amount;
        $cartItems[] = $result;
        $totalPrice += $amount * $result['price'];
    }

    $_SESSION['cart_total_price'] = $totalPrice;
    echo json_encode(['success' => true, 'cart' => $cartItems, 'totalItems' => $totalItems]);
}