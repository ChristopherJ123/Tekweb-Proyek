<?php
session_start();
include "../src/db.php";
global $conn;

if (isset($_GET['s'])) {
    $searchTerm = trim(htmlspecialchars($_GET['s']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pasar Kaki Lima | All Products</title>
    <link rel="stylesheet" href="styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="poppins-regular bg-slate-300">
    <?php
    if (isset($_SESSION['errors'])) { ?>
        <script>
            Swal.fire({
                title: "Error!",
                text: "<?= implode("<br>", $_SESSION['errors']) ?>",
                icon: "error"
            });
        </script>
        <?php
        unset($_SESSION['errors']);
    }
    ?>

    <?php
    if (isset($_SESSION['success'])) { ?>
        <script>
            Swal.fire({
                title: "Success",
                text: "<?= $_SESSION['success'] ?>",
                icon: "success"
            });
        </script>
        <?php
        unset($_SESSION['success']);
    }
    ?>

    <!--  Top Nav Bar & Scripts  -->
    <?php include '../src/topnavbar.php'?>

    <div class="grid grid-cols-2 sm:flex flex-wrap gap-2 m-4 justify-center">
        <?php
        if (isset($searchTerm)) {
            $queryProducts = "
                        SELECT p.id, p.image_link, p.name, p.price, u.username, u.profile_picture, u.id as author_id 
                        FROM products p 
                        JOIN users u on u.id = p.author
                        WHERE p.name LIKE '%$searchTerm%'
                        ORDER BY p.created_at DESC 
                        ";
        } else {
            $queryProducts = "
                        SELECT p.id, p.image_link, p.name, p.price, u.username, u.profile_picture, u.id as author_id
                        FROM products p 
                        JOIN users u on u.id = p.author
                        ORDER BY p.created_at DESC 
                        ";
        }
        $result = mysqli_query($conn, $queryProducts);
        foreach ($result as $product) {
            $productID = $product['id'];
            $productImage = $product['image_link'];
            $productName = $product['name'];
            $productPrice = number_format($product['price'], 0, ',', '.');
            $authorID = $product['author_id'];
            $authorName = $product['username'];
            $authorPP = $product['profile_picture']
            ?>
            <div class='flex flex-col sm:w-[200px] shadow border p-2 bg-white rounded-lg hover:scale-[1.01] transition'>
                <img onclick="location.href='product.php?p=<?=urlencode($productName)?>&author=<?=urlencode($authorName)?>'" class='w-[200px] h-[200px] object-cover object-center' src='<?= !empty($productImage) ? $productImage : 'https://cdn.dribbble.com/users/3512533/screenshots/14168376/web_1280___8_4x.jpg'?>' alt='product'>
                <div class="flex flex-col h-full justify-between">
                    <a href="product.php?p=<?=urlencode($productName)?>&author=<?=urlencode($authorName)?>" class='overflow-hidden text-ellipsis line-clamp-3 mb-3 min-h-[3em] text-sm sm:text-base'> <?=$productName?> </a>
                    <div class="flex items-center gap-2">
                        <img class='w-[30px] h-[30px] object-cover object-center rounded-3xl' src='<?=$authorPP?>' alt='pp'>
                        <a href="profile.php?p=<?=urlencode($authorName)?>" class="text-ellipsis overflow-hidden text-sm sm:text-base"><?=$authorName?></a>
                        <a href="chat.php?target=<?=$authorID?>">
                            <span class="material-symbols-outlined text-base">
                                chat
                            </span>
                        </a>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <div class="text-sm sm:text-base">Rp<?=$productPrice?></div>
                        <div class="flex items-center h-5/6">
                            <button onclick="addOrDecreaseProduct(<?=$productID?>, -1)" class="flex border-e border-orange-500 text-white bg-orange-500 rounded-l-xl px-1 h-5 w-5">
                                <span class="material-symbols-outlined text-sm">
                                    remove
                                </span>
                            </button>
                            <div id="product<?=$productID?>" class="px-1.5 text-orange-500 border-y border-orange-500 text-sm h-5">0</div>
                            <button onclick="addOrDecreaseProduct(<?=$productID?>, 1)" class="flex border-s border-orange-500 text-white bg-orange-500 rounded-r-xl px-0.5 h-5 w-5">
                                <span class="material-symbols-outlined text-sm">
                                    add
                                </span>
                            </button>
                        </div>
                    </div>
                    <button onclick="addToCart(<?=$productID?>)" class="flex text-sm items-center p-2 text-orange-500 border border-orange-500 hover:text-white hover:bg-orange-500 transition duration-75">
                        <span class="material-symbols-outlined text-sm">
                            add
                        </span>
                        <span>
                            Masukkan keranjang
                        </span>
                    </button>
                </div>
            </div>
        <?php }
        ?>
    </div>
</body>
</html>