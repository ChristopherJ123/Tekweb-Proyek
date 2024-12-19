<?php
include "../src/db.php";
global $conn;
session_start();

if (!isset($_SESSION['user_id'])) {
    $errors = [];
    $errors[] = "Silahkan login terlebih dahulu";
    $_SESSION['errors'] = $errors;
    header("Location: login.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat | PasarKakiLima</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: white;
        }
        .body {
            background-color: white;
            box-shadow: 0 4px 30px lightgray;
            border-radius: 16px;
            min-height: 90vh;
            margin: 2em;
            padding-bottom: 2em;
        }
        .left-side {
            width: 40%;
            padding: 0em;
        }
        .right-side {
            position: relative;
            width: 60%;
            border-left: 1px solid lightgray;
        }

        .profile-circle {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin: 0.2em 0.4em 0.2em 0.6em;
            background-color: #cccccc;
        }

        .chat-profile-stacks {
            margin: 0.1em 0.8em 0.1em 0em;
        }

        .chat-profile-stack {
            display: flex;
            align-items: center;

            width: 100%;
            height: 60px;
            background-color: #f3c10c;
            border-radius: 16px;
            margin: 1em 0em 1em 0.4em;
        }

        .chat-profile-page {
            display: flex;
            justify-content: start;
            align-items: start;

            width: 100%;
            margin: 0.4em 0em 0em 0em;
        }

        .user-message-box {
            display: flex;
            justify-content: end;
        }

        .user-message {
            display: flex;
            justify-content: end;

            width: auto;
            max-width: 60%;
            font: medium Verdana;
            border-radius: 16px 16px 0px 16px;
            background-color: lightgray;
            padding: 10px 10px 0px 10px;
            margin: 10px;
        }

        .target-message-box {
            display: flex;
            justify-content: start;
        }

        .target-message {
            display: flex;
            justify-content: end;

            width: auto;
            max-width: 60%;
            font: medium Verdana;
            border-radius: 16px 16px 16px 0px;
            background-color: deepskyblue;
            padding: 10px 10px 0px 10px;
            margin: 10px;
        }

        .input-message-box {
            position: absolute;
            width: 100%;
            bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-right: 10px;
        }

        .input-message {
            display: flex;
            justify-content: start;
            align-items: center;
            float: left;
            resize: none;
            min-height: 44px;

            width: 100%;
            /*max-width: 1000px;*/
            height: 5vh;
            font: medium Verdana;
            border-radius: 16px;
            border: 2px solid gray;
            padding: 10px;
            margin: 0em 0.2em 0em 0em;
        }

        .send-button {
            float: right;
            background-color: lawngreen;
            width: 45px;
            height: 45px;
            border-radius: 16px;
            /*padding: 0.3em;*/
            /*margin: 0.2em 0.4em 0.2em 0.6em;*/
        }
        .material-symbols-outlined {
            font-variation-settings:
                    'FILL' 0,
                    'wght' 400,
                    'GRAD' 0,
                    'opsz' 24;
            font-size: xx-large;
        }

        /* Responsiveness */
        @media (max-width: 480px) {
            .user-message {
                font: small Verdana;
            }
            .target-message {
                font: small Verdana;
            }
            small, em {
                font: small Verdana;
            }
            .profile-circle {
                width: 35px;
                height: 35px;
            }
            .profile-circle img {
                width: 24px;
            }
            .chat-profile-stack {
                height: 48px;
            }
            .input-message {
                width: 86%;
                height: 5vh;
                font: small Verdana;
                min-height: 24px;
            }

            .send-button {
                float: right;
                background-color: lawngreen;
                width: 45px;
                height: 45px;
                border-radius: 16px;
                /*padding: 0.3em;*/
                /*margin: 0.2em 0.4em 0.2em 0.6em;*/
            }
        }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['errors'])) { ?>
        <script>
            Swal.fire({
                title: "Error!",
                html: "<?= implode("<br>", $_SESSION['errors']) ?>",
                icon: "error"
            });
        </script>
        <?php
        unset($_SESSION['errors']);
    }
    ?>

    <?php
    if (isset($_SESSION['success'])) { ?>
        <script>
            console.log("test")
            Swal.fire({
                title: "Success",
                html: "<?= $_SESSION['success'] ?>",
                icon: "success"
            });
        </script>
        <?php
        unset($_SESSION['success']);
    }
    ?>
    <a href="javascript:history.back()" class="absolute top-0 left-0">
        <span class="material-symbols-outlined p-2">
            arrow_back
        </span>
    </a>
    <div class="body row">
        <div class="left-side">
            <h2 class="fs-2 fw-bold mx-4 pt-3">Chat</h2>

            <div class="chat-profile-stacks">
                <?php
                $userID = $_SESSION['user_id'];
                $queryGetTargetID = "
                    SELECT DISTINCT users.id, users.username, users.profile_picture FROM private_chats
                    JOIN users ON private_chats.target_id = users.id
                    WHERE user_id = '$userID'
                ";
                $result = mysqli_query($conn, $queryGetTargetID);
                    foreach ($result as $row) { ?>
                        <div onclick="location.href='chat.php?target=<?=$row['id']?>'" class="chat-profile-stack">
                            <span class="profile-circle">
                                <img class="rounded-full" src="<?=$row['profile_picture']?>" alt="pp">
                            </span>
                            <span class="d-flex flex-column">
                                <small><?=$row['username']?></small>
                                <em class="fst-italic">seen</em>
                            </span>
                        </div>
                    <?php } ?>
            </div>
        </div>

        <div class="right-side flex flex-col justify-between">
            <div>
                <?php
                if (isset($_GET['target'])) {
                    $targetID = $_GET['target'];
                    $queryGetTargetID = "
                SELECT users.username, users.profile_picture FROM users
                WHERE id = '$targetID'";
                    $result = mysqli_query($conn, $queryGetTargetID);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div class="chat-profile-page">
                        <a class="profile-circle">
                            <img class="rounded-full" src="<?=$row['profile_picture']?>" alt="">
                        </a>
                        <span class="d-flex flex-column">
                        <small><?=$row['username']?></small>
                        <em class="fst-italic">seen</em>
                        </span>
                    </div>
                    <?php
                }
                ?>

                <?php
                if (isset($_GET['target'])) {
                $userID = $_SESSION['user_id'];
                $targetID = $_GET['target'];
                $queryGetContent = "SELECT private_chats.user_id, private_chats.target_id, private_chats.id, private_chats.content FROM private_chats
                                    WHERE (user_id = '$userID' AND target_id = '$targetID') OR (user_id = '$targetID' AND target_id = '$userID')
                                    ORDER BY private_chats.created_at ASC";
                $result = mysqli_query($conn, $queryGetContent);
                foreach ($result as $row) {
                    if ($row['user_id'] == $userID) { ?>
                        <div class="user-message-box">
                            <div class="user-message">
                                <p><?=$row['content']?></p>
                            </div>
                        </div>
                    <?php }
                    else { ?>
                        <div class="target-message-box">
                            <div class="target-message">
                                <p><?=$row['content']?></p>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>

                <div class="flex w-full">
                    <form action="./scripts/chat_script.php" method="POST" style="width: 100%;">
                        <input type="hidden" name="targetUserID" value="<?=$targetID?>">
                        <div class="flex justify-between items-center gap-2 sm:gap-4">
                            <label class="flex flex-grow-[3]">
                                <input type="text" name="content" class="flex items-center border p-2 resize-none w-full rounded-3xl" placeholder="Message.."></input>
                            </label>
                            <button class="btn send-button" type="submit">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <?php
            }
            ?>




        </div>


    </div>

</body>
</html>