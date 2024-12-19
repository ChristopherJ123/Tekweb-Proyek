<?php
include '../src/db.php';
global $conn;
session_start();

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
        echo $author;

        $productAuthorImageLink = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT DISTINCT users.id, users.username, users.profile_picture FROM users
        JOIN products ON products.author = users.id
        WHERE users.username = '$author'
        "))['profile_picture']; // Ditambahin sendiri, HINT: query nya di join kan sama usersnya, HINT2: ON p.author_id = u.user_id

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
    <title>Product View | PasarKakiLima</title>
    <link rel="stylesheet" href="styles.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Uncial+Antiqua&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 1em;
        }
        .material-symbols-outlined {
            font-variation-settings:
                    'FILL' 0,
                    'wght' 400,
                    'GRAD' 0,
                    'opsz' 24
        }
        .material-symbols-outlined.text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
        .material-symbols-outlined.text-base {
            font-size: 1rem; /* 16px */
            line-height: 1.5rem; /* 24px */
        }
    </style>
</head>
<body class="poppins-regular bg-slate-300">
    <!--  Top Nav Bar & Scripts  -->
    <?php include '../src/topnavbar.php'?>

    <div>
        <?php
        if ($status === 1) { ?>
<!--                <div>--><?php //=$productName?><!--</div>-->
<!--                <div>--><?php //=$productImageLink?><!--</div>-->
<!--                <div>--><?php //=$productQuantity?><!--</div>-->
<!--                <div>--><?php //=$productPrice?><!--</div>-->
<!--                <div>--><?php //=$productCreatedAt?><!--</div>-->
<!--                <br>-->
<!--                <div>--><?php //=$productAuthorName?><!--</div>-->

        <div class="grid grid-cols-2 shadow border p-2 bg-white rounded-lg">
            <div class="bg-blue-300">
                <img class="w-[200px] h-[200px] object-cover object-center" src="<?=$productImageLink?>"></img>
            </div>
            <div>
                <h1><?=$productName?></h1>
            </div>

            <div class="flex items-center gap-2">
                <div class="w-[30px] h-[30px] object-cover object-center rounded-3xl">
                    <img class="" src="<?=$productAuthorImageLink?>" alt="pp">
                </div>
                <h1 class="text-3xl"><?=$productAuthorName?></h1>
            </div>


        </div>

        <?php } else { ?>
            <div>
                Produk tidak ditemukan!
            </div>
        <?php }
        ?>

    </div>
</body>
</html>
