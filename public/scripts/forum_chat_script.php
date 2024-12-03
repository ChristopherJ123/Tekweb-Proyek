<?php
include '../../src/db.php';
global $conn;
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $forumID = trim(htmlspecialchars($_POST['forumID']));
    $content = trim(htmlspecialchars($_POST['content']));
    $errors = [];

    if (isset($_SESSION['user_id'])) {
        $userID = $_SESSION['user_id'];
        $query = "INSERT INTO forum_chats (forum_id, user_id, content) VALUES ('$forumID', '$userID', '$content')";
        try {
            mysqli_query($conn, $query);
            mysqli_close($conn);
            header("Location: ../index.php");
        } catch (mysqli_sql_exception $exception) {
            $errors[] = "Terjadi kesalahan saat pengiriman pesan. Silahkan coba lagi";
        }
    } else {
        $errors[] = "Login terlebih dahulu sebelum mengikuti diskusi";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../register_product.php");
        exit();
    }
}