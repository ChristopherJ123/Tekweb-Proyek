<?php
session_start();
include "../src/db.php";
global $conn
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <?php
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productID => $amount) {
                $queryProduct = "SELECT name, image_link, price FROM products WHERE id = '$productID'";
                $product = mysqli_fetch_assoc(mysqli_query($conn, $queryProduct)); ?>
                <div><?=$product['name']?> <?=$product['image_link']?> <?=$product['price']?> <?=$amount?></div>
            <?php }
        } else { ?>
            <div>Cart kosong</div>
        <?php }
        ?>
    </div>
</body>
</html>
