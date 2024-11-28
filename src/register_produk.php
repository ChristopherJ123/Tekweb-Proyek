<?php
include 'db.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stok'];
    $image = '';

    //jika ada gambar dapat diupload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
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
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $name, $description, $price, $stock, $image);

    if ($stmt->execute()) {
        echo "Produk berhasil ditambahkan!";
    } else {
        echo "Gagal menambahkan produk: " . $conn->error;
    }

    $stmt->close();
}
?>
