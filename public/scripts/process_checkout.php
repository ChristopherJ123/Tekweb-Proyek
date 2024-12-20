<?php
session_start();
include '../../src/db.php';
global $conn;
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['errors'] = ['You need to log in to access this page.'];
    header('Location: ../login.php');
    exit();
}

// Check if address_id is provided
if (!isset($_GET['address_id'])) {
    $_SESSION['errors'] = ['No address selected.'];
    header('Location: checkout.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$address_id = intval($_GET['address_id']);
$total_price = 0;

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Validate the selected address belongs to the user
$queryValidateAddress = "SELECT * FROM address WHERE id = ? AND user_id = ?";
$stmtValidateAddress = mysqli_prepare($conn, $queryValidateAddress);
if ($stmtValidateAddress) {
    mysqli_stmt_bind_param($stmtValidateAddress, 'ii', $address_id, $user_id);
    mysqli_stmt_execute($stmtValidateAddress);
    $resultAddress = mysqli_stmt_get_result($stmtValidateAddress);

    if (mysqli_num_rows($resultAddress) === 0) {
        $_SESSION['errors'] = ['Invalid address selected.'];
        header('Location: public/checkout.php');
        exit();
    }
    mysqli_stmt_close($stmtValidateAddress);
} else {
    die('Failed to validate address.');
}

// Fetch cart items from session
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['errors'] = ['Your cart is empty.'];
    header('Location: public/checkout.php');
    exit();
}

$cartItems = $_SESSION['cart'];

// Start transaction
mysqli_begin_transaction($conn);
try {
    // Insert transaction
    $queryInsertTransaction = "INSERT INTO transactions (user_id, total_price, created_at, updated_at) VALUES (?, 0, NOW(), NOW())";
    $stmtTransaction = mysqli_prepare($conn, $queryInsertTransaction);
    if ($stmtTransaction) {
        mysqli_stmt_bind_param($stmtTransaction, 'i', $user_id);
        mysqli_stmt_execute($stmtTransaction);
        $transaction_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmtTransaction);
    } else {
        throw new Exception('Failed to create transaction.');
    }

    // Insert transaction details
    foreach ($cartItems as $product_id => $quantity) {
        $queryProduct = "SELECT price FROM products WHERE id = ?";
        $stmtProduct = mysqli_prepare($conn, $queryProduct);
        if ($stmtProduct) {
            mysqli_stmt_bind_param($stmtProduct, 'i', $product_id);
            mysqli_stmt_execute($stmtProduct);
            $resultProduct = mysqli_stmt_get_result($stmtProduct);
            $product = mysqli_fetch_assoc($resultProduct);
            mysqli_stmt_close($stmtProduct);

            if ($product) {
                $price = $product['price'];
                $subtotal = $price * $quantity;
                $total_price += $subtotal;

                $queryInsertDetail = "INSERT INTO transaction_items (transaction_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)";
                $stmtDetail = mysqli_prepare($conn, $queryInsertDetail);
                if ($stmtDetail) {
                    mysqli_stmt_bind_param($stmtDetail, 'iiid', $transaction_id, $product_id, $quantity, $subtotal);
                    mysqli_stmt_execute($stmtDetail);
                    mysqli_stmt_close($stmtDetail);
                } else {
                    throw new Exception('Failed to insert transaction details.');
                }
            } else {
                throw new Exception('Product not found.');
            }
        } else {
            throw new Exception('Failed to fetch product.');
        }
    }

    // Update total price in the transaction
    $queryUpdateTotal = "UPDATE transactions SET total_price = ? WHERE id = ?";
    $stmtUpdateTotal = mysqli_prepare($conn, $queryUpdateTotal);
    if ($stmtUpdateTotal) {
        mysqli_stmt_bind_param($stmtUpdateTotal, 'di', $total_price, $transaction_id);
        mysqli_stmt_execute($stmtUpdateTotal);
        mysqli_stmt_close($stmtUpdateTotal);
    } else {
        throw new Exception('Failed to update total price.');
    }

    // Commit transaction
    mysqli_commit($conn);

    // Clear cart session
    unset($_SESSION['cart']);

    // Redirect to index page
    $_SESSION['success'] = 'Transaksi berhasil!';
    header('Location: ../index.php');
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['errors'] = [$e->getMessage()];
    header('Location: ../index.php');
    exit();
}
?>
