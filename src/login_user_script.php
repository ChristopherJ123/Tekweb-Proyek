<?php
session_start();
include 'db.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = trim(htmlspecialchars($_POST['usernameOrEmail']));
    $password = htmlspecialchars($_POST['password']);
    $errors = [];

    if (empty($usernameOrEmail) || empty($password)) {
        $errors[] = "All fields are required!";
    }
    if (str_contains($usernameOrEmail, '@')) {
        $email = $usernameOrEmail;
        $query = "SELECT id, email, username, password FROM users WHERE email = '$email'";
    } else {
        $username = $usernameOrEmail;
        $query = "SELECT id, email, username, password FROM users WHERE username = '$username'";
    }
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = $row['username'];
            $success = "You have succesfully logged in!";
            $_SESSION['success'] = $success;
            mysqli_close($conn);
            header('Location: ../public/index.php');
            exit();
        } else {
            $errors[] = "Your credentials does not match our database!";
            mysqli_close($conn);
        }
    } else {
        $errors[] = "Your credentials does not match our database!";
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../public/login.php');
        exit();
    }
}