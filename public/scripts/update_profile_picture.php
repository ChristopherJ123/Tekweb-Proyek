<?php
include '../../src/db.php';
global $conn;
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if (isset($_SESSION['user_id'])) {
        $image = '';

        // Handle profile picture upload
        if (!empty($_FILES['profile_picture']['name'])) {
            $targetDir = "../images/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true); // Create folder if it doesn't exist
            }

            $fileType = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB Max image size

            // Error handling
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Format gambar tidak didukung.";
            }

            if ($_FILES['profile_picture']['size'] > $maxFileSize) {
                $errors[] = "Ukuran gambar melebihi 2MB.";
            }

            $imageName = uniqid() . '.' . $fileType;
            $targetFilePath = $targetDir . $imageName;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                $image = 'https://pasarkakilima.guraa.me' . '/images/' . $imageName;

                // Update profile picture in the database
                $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $image, $_SESSION['user_id']);

                if ($stmt->execute()) {
                    $success = "Foto profil berhasil diperbarui.";
                    $_SESSION['success'] = $success;
                    $stmt->close();
                    $_SESSION['profile_picture'] = $image;
                    header("Location: ../profile.php");
                    exit();
                } else {
                    $errors[] = "Gagal memperbarui foto profil: " . $stmt->error;
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
        header("Location: ../profile.php");
        exit();
    }
}

?>
