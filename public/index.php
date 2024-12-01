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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Uncial+Antiqua&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=send" />
    <style>
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
         .material-symbols-outlined {
             font-variation-settings:
                     'FILL' 0,
                     'wght' 400,
                     'GRAD' 0,
                     'opsz' 24
         }
    </style>
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->
</head>
<body class="poppins-regular">
    <div class="tw-flex tw-flex-col tw-items-center tw-bg-gray-300 tw-gap-2">
        <?php
        // Products
        $queryForums = "
            SELECT DISTINCT forum_id, u.id, u.username, u.profile_picture FROM forum_products
            JOIN products p ON forum_products.product_id = p.id
            JOIN users u ON p.author = u.id
            ";
        $forums = mysqli_query($conn, $queryForums);
        foreach ($forums as $forum) {
            $queryProductsFromAuthor = "SELECT * FROM products WHERE author = {$forum['id']}";
            $products = mysqli_query($conn, $queryProductsFromAuthor);

            $forumID = $forum['forum_id'];
            $authorName = $forum['username'];
            $authorPP = $forum['profile_picture'];
            ?>
            <div class='tw-flex tw-flex-col tw-gap-2 tw-bg-white tw-p-2 tw-rounded-lg tw-shadow'>
                <div class='tw-flex tw-items-center tw-gap-2'>
                    <img class='tw-w-[30px] tw-h-[30px] tw-object-cover tw-object-center tw-rounded-3xl' src='<?=$authorPP?>' alt='pp'>
                    <a href='#'><?=$authorName?></a>
                </div>
                <div class='tw-grid tw-grid-cols-3 tw-gap-2'>
                    <?php
                    foreach ($products as $product) {
                        $productImage = $product['image_link'];
                        $productName = $product['name'];
                        $productPrice = number_format($product['price'], 0, ',', '.');
                        ?>
                        <div class='tw-flex tw-flex-col tw-w-[200px] tw-shadow tw-border tw-p-2'>
                            <img class='tw-w-[200px] tw-h-[200px] tw-object-cover tw-object-center' src='<?=$productImage?>' alt='product'>
                            <div class="tw-flex tw-flex-col tw-h-full tw-justify-between">
                                <div class='tw-overflow-hidden tw-text-ellipsis tw-line-clamp-3 tw-mb-3'> <?=$productName?> </div>
                                <div> Rp<?=$productPrice?> </div>
                            </div>

                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="tw-flex tw-flex-col tw-gap-2 tw-border tw-p-2 tw-shadow">
                    <div>
                        <p class="tw-mb-2">Discussion:</p>
                        <div class="tw-flex tw-flex-col tw-gap-1">
                            <?php
                            $queryForumChats = "
                                SELECT * FROM forum_chats 
                                JOIN users u on u.id = forum_chats.user_id
                                WHERE forum_id = $forumID";
                            $forumChats = mysqli_query($conn, $queryForumChats);
                            foreach ($forumChats as $forumChat) {
                                $forumChatPP = $forumChat['profile_picture'];
                                $forumChatUsername = $forumChat['username'];
                                $forumChatContent = $forumChat['content'];
                                ?>
                                <div class='tw-flex tw-items-center tw-gap-2'>
                                    <img class='tw-w-[30px] tw-h-[30px] tw-object-cover tw-object-center tw-rounded-3xl' src='<?=$forumChatPP?>' alt='pp'>
                                    <a href="#"><?=$forumChatUsername?></a>
                                    <div>
                                        <?=$forumChatContent?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <form action="" method="POST">
                        <div class="tw-flex tw-justify-between tw-border">
                            <div class="tw-w-full">
                                <label for="forumChat<?=$forumID?>"></label>
                                <input type="text" id="forumChat<?=$forumID?>" class="tw-w-full tw-p-2" name="content" placeholder="Chat here">
                            </div>
                            <button>
                                <span class="material-symbols-outlined">
                                    send
                                </span>
                            </button>
                        </div>
                        <input type="hidden" name="forumID" value="<?=$forumID?>">
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>