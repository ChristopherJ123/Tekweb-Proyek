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
        $productID_ = $row['id'];
        $productDescription = $row['description'];
        $productImageLink = $row['image_link'];
        $productQuantity = $row['quantity_in_stock'];
        $productPrice = number_format($row['price'], 0, ',', '.');
        $productCreatedAt = $row['created_at'];
        $productAuthorID = $row['author'];

        $productAuthorName = $author;

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
<body class="poppins-regular bg-slate-300 flex-col">
<!--  Top Nav Bar & Scripts  -->
<?php include '../src/topnavbar.php'?>

<div class="flex flex-col justify-center">

    <div class="flex justify-center">
        <?php
        if ($status === 1) { ?>
            <div class="flex sm:w-[83%] md:max-w-[75%] lg:max-w-[75%] shadow border p-2 mx-4 bg-white rounded-lg">
                <div class="min-w-[100px]">
                    <img class="w-[200px] h-[200px] object-cover object-center" src="<?=$productImageLink?>">
                    <div class="flex flex-col gap-y-2 mt-4">
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-sm sm:text-base">Rp <?=$productPrice?></div>
                            <div class="flex items-center h-5/6">
                                <button onclick="addOrDecreaseProduct(<?=$productID_?>, -1)" class="flex border-e border-orange-500 text-white bg-orange-500 rounded-l-xl px-1 h-5 w-5">
                                    <span class="material-symbols-outlined text-sm">
                                        remove
                                    </span>
                                </button>
                                <div id="product<?=$productID_?>" class="px-1.5 text-orange-500 border-y border-orange-500 text-sm h-5">0</div>
                                <button onclick="addOrDecreaseProduct(<?=$productID_?>, 1)" class="flex border-s border-orange-500 text-white bg-orange-500 rounded-r-xl px-0.5 h-5 w-5">
                                    <span class="material-symbols-outlined text-sm">
                                        add
                                    </span>
                                </button>

                            </div>
                        </div>
                        <div>
                            <button onclick="addToCart(<?=$productID_?>)" class="flex justify-center text-sm w-full items-center p-2 text-orange-500 border border-orange-500 hover:text-white hover:bg-orange-500 transition duration-75">
                        <span class="material-symbols-outlined text-sm">
                            add</span>
                                <span>
                            Masukkan keranjang
                        </span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col mx-4 gap-y-2" >
                    <h1 class="sm:text-2xl md:text-2xl lg:text-2xl font-semibold mt-2"><?=$productName?></h1>
                    <hr>
                    <div class="flex items-center gap-2 mb-2">
                        <img class="w-[30px] h-[30px] object-cover object-center rounded-3xl" src="<?=$productAuthorImageLink?>" alt="pp">
                        <h1 class="sm:text-medium lg:text-lg"><?=$productAuthorName?></h1>
                        <a class="flex align-center" href="chat.php?target=<?=$productAuthorID?>">
                                <span class="material-symbols-outlined">
                                    chat
                                </span>
                        </a>
                    </div>

                    <h1 class="text-3xl font-semibold">Rp <?=$productPrice?></h1>

                    <p class="sm:text-medium md:text-medium lg:text-lg"><?=$productDescription?></p>
                </div>
            </div>
        <?php } else { ?>
            <div>
                Produk tidak ditemukan!
            </div>
        <?php }
        ?>
    </div>


    <div class="grid grid-cols-2 sm:flex flex-wrap gap-2 m-4 justify-center">
        <?php
        $queryProducts = "
                        SELECT p.id, p.image_link, p.name, p.price, u.username, u.profile_picture 
                        FROM products p 
                        JOIN users u on u.id = p.author
                        WHERE p.id != '$productID_'
                        ORDER BY p.created_at DESC 
                        ";
        $result = mysqli_query($conn, $queryProducts);
        foreach ($result as $product) {
            $productID_ = $product['id'];
            $productImage = $product['image_link'];
            $productName = $product['name'];
            $productPrice = number_format($product['price'], 0, ',', '.');
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
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <div class="text-sm sm:text-base">Rp <?=$productPrice?></div>
                        <div class="flex items-center h-5/6">
                            <button onclick="addOrDecreaseProduct(<?=$productID_?>, -1)" class="flex border-e border-orange-500 text-white bg-orange-500 rounded-l-xl px-1 h-5 w-5">
                                    <span class="material-symbols-outlined text-sm">
                                        remove
                                    </span>
                            </button>
                            <div id="product<?=$productID_?>" class="px-1.5 text-orange-500 border-y border-orange-500 text-sm h-5">0</div>
                            <button onclick="addOrDecreaseProduct(<?=$productID_?>, 1)" class="flex border-s border-orange-500 text-white bg-orange-500 rounded-r-xl px-0.5 h-5 w-5">
                                    <span class="material-symbols-outlined text-sm">
                                        add
                                    </span>
                            </button>
                        </div>
                    </div>
                    <button onclick="addToCart(<?=$productID_?>)" class="flex text-sm items-center p-2 text-orange-500 border border-orange-500 hover:text-white hover:bg-orange-500 transition duration-75">
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

</div>
</body>
</html>
