<?php
include 'db.php';
global $conn;
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);
        $price = (float)htmlspecialchars($_POST['price']);
        $stock = (int)htmlspecialchars($_POST['stok']);
        $image = '';

        //jika ada gambar dapat diupload
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../public/images";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true); //membuat folder jika belum ada
            }

            $imageName = basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image = $imageName;
            } else {
                echo "Gagal mengupload gambar.";
                exit;
            }
        }

        //kode untuk memasukkan data ke database
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, quantity_in_stock, image_link, author) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $name, $description, $price, $stock, $image, $_SESSION['user_id']);

        if ($stmt->execute()) {
            echo "Produk berhasil ditambahkan!";
        } else {
            echo "Gagal menambahkan produk: " . $conn->error;
        }

        $stmt->close();
    }
} else {
    echo "Anda belum login. Mohon login terlebih dahulu.";
}

