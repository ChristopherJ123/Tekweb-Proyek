<?php
include 'db.php';
global $conn;
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if (isset($_SESSION['user_id'])) {
        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);
        $price = (float)htmlspecialchars($_POST['price']);
        $stock = (int)htmlspecialchars($_POST['stok']);
        $image = '';

        //jika ada gambar dapat diupload
        if (!empty($_FILES['gambar']['name'])) {
            $targetDir = "../public/images/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true); //membuat folder jika belum ada
            }

            $fileType = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB Max image size

            // Error handling
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Format gambar tidak di support";
            }

            if ($_FILES['gambar']['size'] > $maxFileSize) {
                $errors[] = "Ukuran gambar melebihi 2MB";
            }

            $imageName = uniqid() . 'src' . $fileType;
            $targetFilePath = $targetDir . $imageName;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFilePath)) {
                $image = 'images/' . $imageName;

                //kode untuk memasukkan data ke database
                $stmt = $conn->prepare("INSERT INTO products (name, description, price, quantity_in_stock, image_link, author) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $image, $_SESSION['user_id']);

                if ($stmt->execute()) {
                    $success = "Produk berhasil ditambahkan!";
                    $_SESSION['success'] = $success;
                    $stmt->close();
                    header("Location: ../public/index.php");
                    exit();
                } else {
                    $errors[] = "Gagal menambahkan produk: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $errors[] = "Gagal mengupload gambar.";
            }
        } else {
            $errors[] = "Gambar tidak boleh kosong.";
        }
    } else {
        $errors[] = "Anda belum login. Mohon login terlebih dahulu.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../public/register_product.php");
        exit();
    }
}

