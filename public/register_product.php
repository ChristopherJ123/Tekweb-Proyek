<?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--P.s Warna bisa diganti nanti, sama jika ada bagian yang mau diganti silahkan-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasar Kaki Lima | Registrasi Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
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
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Registrasi Produk</h1>
        <form action="scripts/register_product_script.php" method="POST" enctype="multipart/form-data">
            <!--bagian kode ini untuk bagian front end dimana user dapat menginput nama produk yang akan dijual-->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" id="name" name="name" class="mt-1 border block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>
            <!--bagian kode ini untuk bagian front end dimana user dapat menginput deskripsi untuk memberi penjelasan pada produk yang dijual-->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="mt-1 border block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
            </div>
            <!--bagian kode ini untuk bagian front end dimana user menginput harga barang yang dijual per unit/biji/buah/satu barang-->
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" id="price" name="price" step="0.01" class="mt-1 border block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>
            <!--bagian kode ini untuk bagian front end dimana user dapat menginput berapa banyak barang yang ingin dijual-->
            <div class="mb-4">
                <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                <input type="number" id="stok" name="stok" class="mt-1 border block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>
            <!--bagian kode ini untuk bagian front end dimana user dapat memasukkan gambar-->
            <div class="mb-4">
                <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <!--bagian kode ini untuk bagian front end tombol submit untuk mengsubmit produknya yang ingin dijual-->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Tambah Produk</button>
        </form>
    </div>
    <!--P.s Warna bisa diganti nanti, sama jika ada bagian yang mau diganti silahkan-->
</body>
</html>
