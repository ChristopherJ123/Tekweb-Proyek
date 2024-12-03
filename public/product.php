<?php
include '../src/db.php';
global $conn;

// Dipahami buat UAS ya, sama file2 PHP yang lainnya
if (isset($_GET['p']) && isset($_GET['author'])) {
    // p adalah nama produk
    // author adalah username author
    // Contoh: http://localhost:63342/Tekweb-Proyek/public/product.php?p=Makanan+Hamster+KOMPLIT+BERKUALITAS+1kg+%2F+Complete+High+Quality+Hamster+food+1kg&author=memary
    // Maka:
    // p=Makanan+Hamster+KOMPLIT+BERKUALITAS+1kg+%2F+Complete+High+Quality+Hamster+food+1kg
    // author=memary
    $productName = htmlspecialchars(urldecode($_GET['p'])); // Kita decode format URL nya dulu
    $author = htmlspecialchars(urldecode($_GET['author']));

    $query = "SELECT * FROM products WHERE name = '$productName'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) { // Apabila number of rows dari result lebih dari 1
        $status = 1; // Product ditemukan

        $row = mysqli_fetch_assoc($result); // Mengambil row berikutnya hasil dari query
        $productDescription = $row['description'];
        $productImageLink = $row['image_link'];
        $productQuantity = $row['quantity_in_stock'];
        $productPrice = $row['price'];
        $productCreatedAt = $row['created_at'];

        $productAuthorName = $author;
        $productAuthorImageLink = ""; // Ditambahin sendiri, HINT: query nya di join kan sama usersnya, HINT2: ON p.author_id = u.user_id
    } else {
        $status = 0; // Product tidak ditemukan
    }
} else {
    header('Location: allproducts.php');
    mysqli_close($conn);
    exit();
}
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
        if ($status === 1) { ?>
            <div><?=$productName?></div>
            <div><?=$productImageLink?></div>
            <div><?=$productQuantity?></div>
            <div><?=$productPrice?></div>
            <div><?=$productCreatedAt?></div>
            <br>
            <div><?=$productAuthorName?></div>
        <?php } else { ?>
            <div>
                Produk tidak ditemukan!
            </div>
        <?php }
        ?>
    </div>
</body>
</html>
