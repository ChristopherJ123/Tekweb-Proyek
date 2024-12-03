<?php
include '../src/db.php';
global $conn;
session_start();

if (!isset($_SESSION['user_id'])) {
    $errors = [];
    $errors[] = "Login terlebih dahulu";
    $_SESSION['errors'] = $errors;
    header("Location: ../register_product.php");
    exit();
}

$userID = $_SESSION['user_id'];
$query = "SELECT * FROM private_chats WHERE user_id = '$userID'";
$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <?php
        foreach ($result as $row) { ?>
            <div>User: <?=$row['content']?></div>
        <?php }
        ?>
    </div>
</body>
</html>
