<?php
global $conn;
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
        if (isset($_SESSION['cart'])) {
            $cartItems = [];
            foreach ($_SESSION['cart'] as $productID => $amount) {
                $queryProduct = "SELECT name, image_link, price FROM products WHERE id = '$productID'";
                $result = mysqli_fetch_assoc(mysqli_query($conn, $queryProduct));
                $result['quantity'] = $amount;
                $cartItems[] = $result;
            }
        }
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
                <div id="cartDrpDwn" class="absolute flex flex-col gap-2 p-4 top-10 right-1/2 transform <?=isset($_SESSION['cart']) ? 'translate-x-1/4' : 'translate-x-1/2'?> rounded bg-white shadow border" style="display: none">
                    <?php
                    if (isset($_SESSION['cart'])) {
                        foreach ($cartItems as $cartItem) { ?>
                            <div class="flex p-2 border text-sm gap-2">
                                <img class='w-[40px] h-[40px] object-cover object-center' src='<?=$cartItem['image_link']?>' alt='product'>
                                <div class="line-clamp-2 w-64"><?=$cartItem['name']?></div>
                                <div><?=$cartItem['quantity']?>x<?=$cartItem['price']?></div>
                            </div>
                        <?php }
                    }
                    ?>
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
            <div class="relative">
                <div id="cart">
                    <span class="material-symbols-outlined">
                        shopping_bag
                    </span>
                </div>
                <div id="cartDrpDwn" class="absolute flex flex-col gap-2 p-4 top-10 right-1/2 transform <?=isset($_SESSION['cart']) ? 'translate-x-1/4' : 'translate-x-1/2'?> rounded bg-white shadow border" style="display: none">
                    <?php
                    if (isset($_SESSION['cart'])) {
                        foreach ($cartItems as $cartItem) { ?>
                            <div class="flex p-2 border text-sm gap-2">
                                <img class='w-[40px] h-[40px] object-cover object-center' src='<?=$cartItem['image_link']?>' alt='product'>
                                <div class="line-clamp-2 w-64"><?=$cartItem['name']?></div>
                                <div><?=$cartItem['quantity']?>x<?=$cartItem['price']?></div>
                            </div>
                        <?php } ?>
                        <div class="flex justify-end">
                            Subtotal price: <?=$_SESSION['cart_total_price']?>
                        </div>
                        <a href="#" class="flex justify-center p-2 bg-amber-500 rounded-3xl text-white border border-amber-500 hover:text-amber-500 hover:bg-white transition">
                            Checkout
                        </a>
                    <?php } else { ?>
                        <div>Tambahkan barang ke keranjangmu!</div>
                    <?php }
                    ?>
                </div>
            </div>
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
    <?php
    $currentPage = basename($_SERVER['SCRIPT_NAME']);
    if ($currentPage == "index.php") { ?>
        <div class="flex px-2 m-1 ml-8 rounded-2xl bg-white text-amber-500">
            Forum
        </div>
        <a href="allproducts.php" class="flex px-2 m-1 rounded-2xl text-white border-2 border-white">
            Lihat semua produk
        </a>
    <?php } else if ($currentPage == "allproducts.php") { ?>
        <a href="index.php" class="flex px-2 m-1 ml-8 rounded-2xl text-white border-2 border-white">
            Forum
        </a>
        <div class="flex px-2 m-1 rounded-2xl bg-white text-amber-500">
            Lihat semua produk
        </div>
    <?php } else { ?>
        <a href="index.php" class="flex px-2 m-1 ml-8 rounded-2xl text-white border-2 border-white">
            Forum
        </a>
        <a href="allproducts.php" class="flex px-2 m-1 rounded-2xl text-white border-2 border-white">
            Lihat semua produk
        </a>
    <?php }
    ?>
</div>
<!--  HEADER END  -->