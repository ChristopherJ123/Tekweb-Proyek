<?php
session_start();
include "../src/db.php";
global $conn
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
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
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->
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

    <!--  HEADER  -->
    <div class="grid grid-cols-3 p-2 bg-white shadow">
        <div></div>
        <form action="" class="mb-0">
            <div class="flex items-center w-full border rounded">
                <label for="search"></label>
                <span class="material-symbols-outlined p-2 text-gray-500">
                    search
                </span>
                <input type="text" id="search" class="w-full h-full p-2" placeholder="Cari di PasarKakiLima">
            </div>
        </form>
        <div class="flex justify-end items-center gap-2 mr-8">
            <?php
                if (isset($_SESSION['user_id'])) { ?>
                    <a href="register_product.php" class="flex text-sm p-2 border border-dashed border-black bg-black text-white hover:bg-white hover:text-black transition">
                        <span class="material-symbols-outlined text-sm pr-1">
                            add_circle
                        </span>
                        Tambahkan Produk
                    </a>
                    <div class="relative">
                        <div id="cart">
                            <span class="material-symbols-outlined">
                                shopping_bag
                            </span>
                        </div>
                        <div id="cartDrpDwn" class="absolute flex flex-col gap-2 p-4 top-8 rounded bg-white shadow border" style="display: none">
                            p
                        </div>
                    </div>
                    <img class='w-[30px] h-[30px] object-cover object-center rounded-3xl' src='<?=$_SESSION['profile_picture']?>' alt='pp'>
                    <div class="relative flex justify-end">
                        <a id="profile" href="#"><?=$_SESSION['username']?></a>
                        <div id="profileDrpDwn" class="absolute flex flex-col gap-2 p-4 top-8 rounded bg-white shadow border" style="display: none">
                            <div class="flex gap-2">
                                <span class="material-symbols-outlined">
                                    account_circle
                                </span>
                                <a href="#">Profil saya</a>
                            </div>
                            <div class="flex gap-2">
                                <span class="material-symbols-outlined">
                                    settings
                                </span>
                                <a href="#">Pengaturan</a>
                            </div>
                            <div class="flex gap-2">
                                <span class="material-symbols-outlined">
                                    logout
                                </span>
                                <a href="logout.php">Logout</a>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <a href="login.php" class="p-2 border rounded">
                        Masuk
                    </a>
                    <a href="register.php" class="p-2 border rounded">
                        Daftar
                    </a>
                    <button>
                        <span class="material-symbols-outlined">
                            account_circle
                        </span>
                    </button>
                <?php }
            ?>
        </div>
    </div>
    <div class="flex mb-4 bg-amber-500 shadow">
        <div class="flex px-2 m-1 ml-8 rounded-2xl bg-white text-amber-500">
            Forum
        </div>
        <a href="allproducts.php" class="flex px-2 m-1 rounded-2xl text-white border-2 border-white">
            Lihat semua produk
        </a>
    </div>
    <!--  HEADER END  -->

    <div class="flex flex-col items-center gap-2">
        <?php
        // Products
        $queryForums = "
            SELECT DISTINCT forum_id, u.id, u.username, u.profile_picture FROM forum_products
            JOIN products p ON forum_products.product_id = p.id
            JOIN users u ON p.author = u.id
            ";
        $forums = mysqli_query($conn, $queryForums);
        foreach ($forums as $forum) {
            $queryProductsFromAuthor = "SELECT * FROM products WHERE author = {$forum['id']}";
            $products = mysqli_query($conn, $queryProductsFromAuthor);

            $forumID = $forum['forum_id'];
            $authorName = $forum['username'];
            $authorPP = $forum['profile_picture'];
            ?>
            <div class='flex flex-col gap-2 bg-white p-2 rounded-lg shadow'>
                <div class='flex items-center gap-2'>
                    <img class='w-[30px] h-[30px] object-cover object-center rounded-3xl' src='<?=$authorPP?>' alt='pp'>
                    <a href='#'><?=$authorName?></a>
                </div>
                <div class='grid grid-cols-3 gap-2'>
                    <?php
                    foreach ($products as $product) {
                        $productID = $product['id'];
                        $productImage = $product['image_link'];
                        $productName = $product['name'];
                        $productPrice = number_format($product['price'], 0, ',', '.');
                        ?>
                        <div class='flex flex-col w-[200px] shadow border p-2'>
                            <img class='w-[200px] h-[200px] object-cover object-center' src='<?=$productImage?>' alt='product'>
                            <div class="flex flex-col h-full justify-between">
                                <div class='overflow-hidden text-ellipsis line-clamp-3 mb-3 min-h-[3em]'> <?=$productName?> </div>
                                <div class="flex justify-between items-center">
                                    <div>Rp<?=$productPrice?></div>
                                    <div class="flex border border-orange-500 rounded h-5/6 mb-2">
                                        <button onclick="addOrDecreaseProduct(<?=$productID?>, -1)" class="border-e border-orange-500 text-white bg-orange-500 px-1">
                                            <span class="material-symbols-outlined text-sm">
                                                remove
                                            </span>
                                        </button>
                                        <div id="product<?=$productID?>" class="px-1.5 text-orange-500 text-sm">0</div>
                                        <button onclick="addOrDecreaseProduct(<?=$productID?>, 1)" id="decreaseProduct<?=$productID?>" class="border-s border-orange-500 text-white bg-orange-500 px-1">
                                            <span class="material-symbols-outlined text-sm">
                                                add
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <button class="flex text-sm p-2 text-orange-500 border border-orange-500 hover:text-white hover:bg-orange-500 transition duration-75">
                                    <span class="material-symbols-outlined text-sm">
                                        add
                                    </span>
                                    <span>
                                        Masukkan keranjang
                                    </span>
                                </button>
                            </div>

                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="flex flex-col gap-2 border p-2 shadow">
                    <div>
                        <p class="mb-2">Diskusi:</p>
                        <div class="flex flex-col gap-1">
                            <?php
                            $queryForumChats = "
                                SELECT * FROM forum_chats 
                                JOIN users u on u.id = forum_chats.user_id
                                WHERE forum_id = $forumID";
                            $forumChats = mysqli_query($conn, $queryForumChats);
                            foreach ($forumChats as $forumChat) {
                                $forumChatPP = $forumChat['profile_picture'];
                                $forumChatUsername = $forumChat['username'];
                                $forumChatContent = $forumChat['content'];
                                ?>
                                <div class='flex items-center gap-2'>
                                    <img class='w-[30px] h-[30px] object-cover object-center rounded-3xl' src='<?=$forumChatPP?>' alt='pp'>
                                    <a href="#"><?=$forumChatUsername?></a>
                                    <div>
                                        <?=$forumChatContent?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <form action="" method="POST">
                        <div class="flex justify-between border">
                            <div class="w-full flex items-center">
                                <label for="forumChat<?=$forumID?>"></label>
                                <?php
                                if (isset($_SESSION['user_id'])) { ?>
                                    <img class='w-[30px] h-[30px] object-cover object-center rounded-3xl ml-2' src='<?=$_SESSION['profile_picture']?>' alt='pp'>
                                    <input type="text" id="forumChat<?=$forumID?>" class="w-full p-2" name="content" placeholder="Ngobrol disini">
                                <?php } else { ?>
                                    <input type="text" id="forumChat<?=$forumID?>" class="w-full p-2" name="content" placeholder="Login terlebih dahulu untuk ikut berdiskusi" disabled>
                                <?php }
                                ?>
                            </div>
                            <?php
                            if (isset($_SESSION['user_id'])) { ?>
                                <button>
                                    <span class="material-symbols-outlined">
                                        send
                                    </span>
                                </button>
                            <?php } ?>
                        </div>
                        <input type="hidden" name="forumID" value="<?=$forumID?>">
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $('#profile').hover(
            function () {
                $('#profileDrpDwn').fadeIn(200);
                $('#cartDrpDwn').fadeOut(200);
            }
        )

        $('#profileDrpDwn').mouseleave(
            function () {
                $('#profileDrpDwn').fadeOut(200);
            }
        )

        $('#cart').hover(
            function () {
                $('#cartDrpDwn').fadeIn(200);
                $('#profileDrpDwn').fadeOut(200);
            }
        )

        $('#cartDrpDwn').mouseleave(
            function () {
                $('#cartDrpDwn').fadeOut(200);
            }
        )

        function addOrDecreaseProduct(id, amount) {
            let product = $('#product' + id);
            if (product.text() === '0' && amount === -1) return;
            product.text(parseInt(product.text()) + amount)
        }
    </script>
</body>
</html>