<?php
global $conn;
session_start();
include '../../src/db.php';

$productID = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_INT);

if ($productID && $amount) {
    $queryProduct = "SELECT quantity_in_stock FROM products WHERE id = '$productID'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $queryProduct));
    if (isset($_SESSION['cart'][$productID])) {
        if ($_SESSION['cart'][$productID] + $amount > 0) {
            if ($_SESSION['cart'][$productID] + $amount > $result['quantity_in_stock']) {
                echo json_encode(['success' => false, 'message' => 'Maaf, stok barang hanya tersisa ' . $result['quantity_in_stock'] . ' buah.']);
                exit();
            }
            $_SESSION['cart'][$productID] += $amount;
            updateCart();
        } else {
            echo json_encode(['success' => false, 'message' => 'Jumlah item tidak boleh negatif!']);
        }
    } else if ($amount > 0) {
        if ($amount > $result['quantity_in_stock']) {
            echo json_encode(['success' => false, 'message' => 'Maaf, stok barang hanya tersisa ' . $result['quantity_in_stock'] . ' buah.']);
            exit();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
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
    $itemsCount = count($_SESSION['cart']);
    $totalPrice = 0;

    $cartItems = [];
    foreach ($_SESSION['cart'] as $prodID => $amount) {
        $queryProduct = "SELECT name, image_link, price FROM products WHERE id = '$prodID'";
        $result = mysqli_fetch_assoc(mysqli_query($conn, $queryProduct));
        $result['quantity'] = $amount;
        $cartItems[] = $result;
        $totalPrice += $amount * $result['price'];
    }

    $_SESSION['cartSubtotal'] = $totalPrice;
    echo json_encode(['success' => true, 'cart' => $cartItems, 'itemsCount' => $itemsCount, 'subtotal' => $totalPrice]);
}