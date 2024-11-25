<?php
include "../src/db.php";
global $conn
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="flex flex-col items-center bg-gray-300 gap-2">
        <?php
        // Products
        $queryProductAuthors = "
            SELECT DISTINCT author, u.username FROM products
            JOIN users u on u.id = products.author
            ";
        $authors = mysqli_query($conn, $queryProductAuthors);
        foreach ($authors as $author) {
            $queryProductsFromAuthor = "SELECT * FROM products WHERE author = {$author['author']}";
            $products = mysqli_query($conn, $queryProductsFromAuthor);
            $authorName = $author['username'];

            echo "
            <div class='bg-white p-2 rounded-lg'>
                <div> $authorName </div>
                <div class='grid grid-cols-3 gap-2'>
            ";
            foreach ($products as $product) {
                $image = $product['image_link'];
                $title = $product['name'];
                echo "
                <div class='w-[200px]'>
                    <img class='w-[200px] h-[200px] object-cover object-center' src='$image' alt='test'>
                    <div class='break-words'> $title </div>
                </div>
                ";
            }
            echo "
                </div>
            </div>
            ";
        }
        ?>


    </div>
</body>
</html>