<?php
session_start();
include '../../src/db.php';
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    if (isset($_SESSION['user_id'])) {
        if (isset($_POST["content"]) && isset($_POST["targetUserID"])) {
            $userID = $_SESSION['user_id'];
            $message = $_POST["content"];
            $targetUserID = $_POST["targetUserID"];

            $queryInsert = "INSERT INTO private_chats (user_id, target_id, content) VALUES ('$userID', '$targetUserID', '$message')";
            if (mysqli_query($conn, $queryInsert)) {
                mysqli_close($conn);
                header('Location: ../chat.php?target='.$targetUserID);
                exit();
            }

        } else {
            $errors[] = "An error has occurred!";
        }
    } else {
        $errors[] = "You need to login first!";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../chat.php");
        exit();
    }
}
?>