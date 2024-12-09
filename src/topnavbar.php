<?php
global $conn;
?>
<!--  HEADER  -->
<div class="grid grid-cols-[1fr_3fr_1fr] sm:grid-cols-[1fr_2fr_1fr] lg:grid-cols-3 p-2 bg-white shadow">
    <div class="sm:ml-6">
        <svg id="Layer_1" height="48px" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="-10 -10 110 280">
            <defs>
                <style>
                    .cls-1 {
                        fill: none;
                        stroke: #000;
                        stroke-miterlimit: 10;
                        stroke-width: 10px;
                    }
                    .cls-2 {
                        fill: #231f20;
                    }
                </style>
            </defs>
            <path class="cls-1" d="M86.58,71.62A67.91,67.91,0,0,1,5.25,138.14,3.16,3.16,0,0,1,2.71,135V8.21a3.16,3.16,0,0,1,2.54-3.1A67.86,67.86,0,0,1,86.58,71.62Z" transform="translate(-0.7 -1.76)"/>
            <line class="cls-1" x1="18.77" y1="192.04" x2="64.78" y2="119.04"/>
            <path class="cls-1" d="M2.7,175.8l82.72,83.87a.69.69,0,0,1-.49,1.18h-81a1.22,1.22,0,0,1-1.22-1.22V7.79" transform="translate(-0.7 -1.76)"/>
            <circle class="cls-2" cx="40.68" cy="32.12" r="4.96"/>
        </svg>
    </div>
    <form action="../public/allproducts.php" method="get" class="mb-0">
        <div class="flex items-center w-full border rounded">
            <label for="search"></label>
            <span class="material-symbols-outlined p-2 text-gray-500">
                search
            </span>
            <input type="search" id="search" name="s" class="w-full h-full p-2" placeholder="Cari di PasarKakiLima">
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
            <a href="register_product.php" class="hidden lg:flex text-sm p-2 border border-dashed border-black bg-black text-white hover:bg-white hover:text-black transition">
                <span class="material-symbols-outlined text-sm pr-1">
                    add_circle
                </span>
                Tambahkan Produk
            </a>
            <div class="relative">
                <div id="cart" class="relative cursor-pointer">
                    <span class="material-symbols-outlined">
                        shopping_bag
                    </span>
                    <span id="cartItemsCount" class="absolute left-0 bottom-0 text-xs px-1 bg-red-500 text-white font-bold rounded-full <?=!isset($_SESSION['cart']) ? 'hidden' : ''?>">
                        <?=isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0?>
                    </span>
                </div>
                <div id="cartDrpDwn" class="absolute flex flex-col gap-2 p-4 top-10 right-1/2 min-w-[200px] sm:min-w-[400px] transform translate-x-1/2 <?=isset($_SESSION['cart']) ? 'sm:translate-x-1/4' : 'translate-x-1/2'?> rounded bg-white shadow border" style="display: none">
                    <div id="cartItems" class="grid grid-cols-1 border divide-y <?= !isset($_SESSION['cart']) ? 'hidden' : ''?>">
                        <?php
                        if (isset($_SESSION['cart'])) {
                            foreach ($cartItems as $cartItem) { ?>
                                <div class="flex p-2 text-sm gap-2">
                                    <img class='w-[40px] h-[40px] object-cover object-center' src='<?=$cartItem['image_link']?>' alt='product'>
                                    <div class="line-clamp-2 w-64"><?=$cartItem['name']?></div>
                                    <div><?=$cartItem['quantity']?>x<?=$cartItem['price']?></div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <?php if (!isset($_SESSION['cart'])) { ?>
                        <div id="emptyCartLabel">Tambahkan barang ke keranjangmu!</div>
                    <?php } ?>
                    <div id="checkoutNav" <?= !isset($_SESSION['cart']) ? 'class="hidden"' : ""?>>
                        <div class="flex justify-end">
                            Subtotal: <span id="cartSubtotal"> <?= $_SESSION['cartSubtotal'] ?? 0 ?> </span>
                        </div>
                        <a href="../public/checkout.php" class="flex justify-center p-2 bg-amber-500 rounded-3xl text-white border border-amber-500 transition duration-75 hover:bg-white hover:text-amber-500">
                            Lihat keranjang
                        </a>
                    </div>
                </div>
            </div>
            <img class='w-[30px] h-[30px] object-cover object-center rounded-3xl' src='<?=$_SESSION['profile_picture']?>' alt='pp'>
            <div class="relative flex justify-end">
                <a id="profile" href="#"><?=$_SESSION['username']?></a>
                <div id="profileDrpDwn" class="absolute flex flex-col gap-2 p-4 top-8 rounded bg-white shadow border" style="display: none">
                    <div class="flex lg:hidden gap-2">
                        <span class="material-symbols-outlined">
                            add_circle
                        </span>
                        <a href="../public/register_product.php">Tambahkan produk</a>
                    </div>
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
                <div id="cart" class="relative">
                    <span class="material-symbols-outlined">
                        shopping_bag
                    </span>
                    <span id="cartItemsCount" class="absolute left-0 bottom-0 text-xs px-1 bg-red-500 text-white font-bold rounded-full <?=!isset($_SESSION['cart']) ? 'hidden' : ''?>">
                        <?=isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0?>
                    </span>
                </div>
                <div id="cartDrpDwn" class="absolute flex flex-col gap-2 p-4 top-10 right-1/2 min-w-[400px] transform <?=isset($_SESSION['cart']) ? 'translate-x-1/4' : 'translate-x-1/2'?> rounded bg-white shadow border" style="display: none">
                    <div id="cartItems" class="grid grid-cols-1 border divide-y <?= !isset($_SESSION['cart']) ? 'hidden' : ''?>">
                        <?php
                        if (isset($_SESSION['cart'])) {
                            foreach ($cartItems as $cartItem) { ?>
                                <div class="flex p-2 text-sm gap-2">
                                    <img class='w-[40px] h-[40px] object-cover object-center' src='<?=$cartItem['image_link']?>' alt='product'>
                                    <div class="line-clamp-2 w-64"><?=$cartItem['name']?></div>
                                    <div><?=$cartItem['quantity']?>x<?=$cartItem['price']?></div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <?php if (!isset($_SESSION['cart'])) { ?>
                        <div id="emptyCartLabel">Tambahkan barang ke keranjangmu!</div>
                    <?php } ?>
                    <div id="checkoutNav" <?= !isset($_SESSION['cart']) ? 'class="hidden"' : ""?>>
                        <div class="flex justify-end">
                            Subtotal: <span id="cartSubtotal"> <?= $_SESSION['cartSubtotal'] ?? 0 ?> </span>
                        </div>
                        <a href="../public/checkout.php" class="flex justify-center p-2 bg-amber-500 rounded-3xl text-white border border-amber-500 transition duration-75 hover:bg-white hover:text-amber-500">
                            Lihat keranjang
                        </a>
                    </div>
                </div>
            </div>
            <a href="login.php" class="p-2 border rounded hidden md:flex">
                Masuk
            </a>
            <a href="register.php" class="p-2 border rounded hidden md:flex">
                Daftar
            </a>
            <div class="relative">
                <span class="material-symbols-outlined">
                    account_circle
                </span>
                <span id="signInNavHover" class="absolute left-0 w-6 h-6 cursor-pointer md:hidden"></span>
                <div id="signInDrpDown" class="absolute right-0 flex flex-col bg-white shadow gap-2 p-4 rounded" style="display: none">
                    <a href="login.php" class="flex">
                        <span class="material-symbols-outlined">
                            account_circle
                        </span>
                        Masuk
                    </a>
                    <a href="register.php" class="flex">
                        <span class="material-symbols-outlined">
                            login
                        </span>
                        Daftar
                    </a>
                </div>
            </div>
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
<!-- SCRIPTS -->
<script>
    $('#profile').mouseenter(
        function () {
            $('#profileDrpDwn').fadeIn(100);
            $('#cartDrpDwn').fadeOut(100);
        }
    ).click(
        function () {
            $('#profileDrpDwn').fadeToggle(100);
            $('#cartDrpDwn').fadeOut(100);
        }
    )

    $('#profileDrpDwn').mouseleave(
        function () {
            $('#profileDrpDwn').fadeOut(100);
        }
    )

    $('#cart').mouseenter(
        function () {
            $('#cartDrpDwn').fadeIn(100);
            $('#profileDrpDwn').fadeOut(100);
            $('#signInDrpDown').fadeOut(100);
        }
    ).click(
        function () {
            $('#cartDrpDwn').fadeToggle(100);
            $('#profileDrpDwn').fadeOut(100);
            $('#signInDrpDown').fadeOut(100);
        }
    )

    $('#cartDrpDwn').mouseleave(
        function () {
            $('#cartDrpDwn').fadeOut(100);
        }
    )

    $('#signInNavHover').hover(
        function () {
            $('#signInDrpDown').fadeIn(100);
            $('#cartDrpDwn').fadeOut(100);
        }
    )

    $('#signInDrpDown').mouseleave(
        function () {
            $('#signInDrpDown').fadeOut(100);
        }
    )

    function addOrDecreaseProduct(id, amount) {
        let product = $('#product' + id);
        if (product.text() === '0' && amount === -1) return;
        product.text(parseInt(product.text()) + amount)
    }

    function addToCart(id) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4) {
                setTimeout(() => {
                    if (this.status === 200) {
                        // console.log(this.responseText) // DEBUG
                        let response = JSON.parse(this.responseText);
                        if (response.success) {
                            $('#emptyCartLabel').hide();
                            $('#cartItems').empty().show();
                            let cartItems = response.cart;
                            cartItems.forEach(item => {
                                const cartItemDiv = document.createElement('div');
                                cartItemDiv.classList.add('flex', 'p-2', 'text-sm', 'gap-2');
                                cartItemDiv.innerHTML = `
                                        <img class='w-[40px] h-[40px] object-cover object-center' src='${item.image_link}' alt='product'>
                                        <div class="line-clamp-2 w-64">${item.name}</div>
                                        <div>${item.quantity}x${item.price}</div>
                                    `;
                                $('#cartItems').append(cartItemDiv)
                            })
                            $('#cartItemsCount').show().text(response.itemsCount);
                            $('#cartSubtotal').text(response.subtotal);
                            $('#checkoutNav').show();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan pada server. Silahkan coba lagi.', 'error')
                    }
                }, 100)
            }
        };
        xmlhttp.open('POST', 'scripts/add_to_cart_script.php')
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send('product_id=' + id + '&amount=' + $('#product' + id).text())
    }
</script>