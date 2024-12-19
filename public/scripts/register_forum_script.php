<?php
session_start();
include '../../src/db.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    if (isset($_SESSION['user_id'])) {
        if (!empty($_POST['selectedProducts'])) {
            $selectedProducts = $_POST['selectedProducts'];

            if (count($selectedProducts) > 6) {
                $errors[] = "Tidak boleh select lebih dari 6 produk!";
            } else {
                $validatedProducts = [];
                $authorID = $_SESSION['user_id'];
                foreach ($selectedProducts as $productID) {
                    $productID = (int)$productID; // Mencegah SQL injection
                    $query = "SELECT * FROM products WHERE author = '$authorID' AND id = '$productID'";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        $validatedProducts[] = mysqli_fetch_assoc($result);
                    }
                }

                if (!empty($validatedProducts)) {
                    $queryInsertForum = "INSERT INTO forums () VALUES ()";
                    $result = mysqli_query($conn, $queryInsertForum);

                    if ($result) {
                        $newForumID = mysqli_insert_id($conn);
                        foreach ($validatedProducts as $product) {
                            $productID = $product['id'];
                            $queryInsertForumProducts = "INSERT INTO forum_products (forum_id, product_id) VALUES ('$newForumID', '$productID')";
                            mysqli_query($conn, $queryInsertForumProducts);
                        }

                        $comment = trim(htmlspecialchars($_POST['comment']));
                        if (!empty($comment)) {
                            $queryInsertForumComment = "INSERT INTO forum_chats (forum_id, user_id, content) VALUES ('$newForumID', '$authorID', '$comment')";
                            mysqli_query($conn, $queryInsertForumComment);
                        }
                        $success = "Forum created successfully!";
                        $_SESSION['success'] = $success;
                        header('Location: ../index.php');
                        exit();
                    } else {
                        $errors[] = mysqli_error($conn);
                    }
                    mysqli_close($conn);
                } else {
                    $errors[] = "Products selected are not valid!";
                }
            }
        } else {
            $errors[] = "No products selected!";
        }
    } else {
        $errors[] = "Login terlebih dahulu!";
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../register_forum.php');
        exit();
    }
}