<?php
session_start();

if (isset($_SESSION['user_id'])) {
    session_destroy();
    session_start();
    $_SESSION['success'] = "Signed out successfully!";
}
header("Location: ../public/index.php");
exit();