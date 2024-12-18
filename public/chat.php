<?php
include "../src/db.php";
global $conn;
session_start();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat | PasarKakiLima</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: white;
        }
        .body {
            background-color: white;
            box-shadow: 0 4px 30px lightgray;
            border-radius: 16px;
            height: 100%;
            margin: 2em;
        }
        .left-side {
            width: 40%;
            padding: 0em;
        }
        .right-side {
            width: 60%;
            border-left: 1px solid lightgray;
        }

        .profile-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            padding: 0.3em;
            margin: 0.2em 0.4em 0.2em 0.6em;
            background-color: #cccccc;
        }
        .profile-circle img {
            width: 40px;
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
            height: 6vh;
            background-color: lime;
            margin: 0em;
        }

        .receiver-message-box {
            display: flex;
            justify-content: end;
        }

        .receiver-message {
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

        .sender-message-box {
            display: flex;
            justify-content: start;
        }

        .sender-message {
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
    </style>
</head>
<body>
    <div class="body row">
        <div class="left-side">
            <h2 class="fs-2 fw-bold mx-4 pt-3">Chat</h2>

            <div class="chat-profile-stacks">
                <div class="chat-profile-stack">
                    <a class="profile-circle">
                        <img src="assets/Bootstrap-person-fill-icon.png" alt="">
                    </a>
                    <span class="d-flex flex-column">
                        <small class="fs-5">John Doe</small>
                        <em class="fst-italic">seen</em>
                    </span>
                </div>
                <div class="chat-profile-stack">
                    <a class="profile-circle">
                        <img src="assets/Bootstrap-person-fill-icon.png" alt="">
                    </a>
                    <span class="d-flex flex-column">
                        <small class="fs-5">John Doe</small>
                        <em class="fst-italic">seen</em>
                    </span>
                </div>
                <div class="chat-profile-stack">
                    <a class="profile-circle">
                        <img src="assets/Bootstrap-person-fill-icon.png" alt="">
                    </a>
                    <span class="d-flex flex-column">
                        <small class="fs-5">John Doe</small>
                        <em class="fst-italic">seen</em>
                    </span>
                </div>

            </div>
        </div>

        <div class="right-side">
            <div class="chat-profile-page">
                <a class="profile-circle">
                    <img src="assets/Bootstrap-person-fill-icon.png" alt="">
                </a>
                <span class="d-flex flex-column">
                        <small class="fs-5">John Doe</small>
                        <em class="fst-italic">seen</em>
                    </span>
            </div>

            <div class="receiver-message-box">
                <div class="receiver-message">
                    <p>Halo kaka saya mau permen</p>
                </div>
            </div>

            <div class="sender-message-box">
                <div class="sender-message">
                    <p>Ready kak. Bisa langsung order saja ya di etalase 2 kami. Buruan checkout sebelum kehabisan!!!</p>
                </div>
            </div>

            <div class="receiver-message-box">
                <div class="receiver-message">
                    <p>Santai gan 😭😭</p>
                </div>
            </div>

        </div>


    </div>

</body>
</html>