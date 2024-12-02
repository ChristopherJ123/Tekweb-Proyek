<?php
session_start();
include '../../src/db.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim( htmlspecialchars($_POST['email']) );
    $username = strtolower( htmlspecialchars(trim($_POST['username'])) );
    $password = trim( htmlspecialchars($_POST['password']) );
    $confirmPassword = trim( htmlspecialchars($_POST['confirm-password']) );
    $phone = trim( htmlspecialchars($_POST['phone']) );
    $errors = [];

    // Validasi input
    if (empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required!";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match!";
    } else {
        // Check email already registered
        $query = "SELECT username, email FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Email already registered!";
        }
        // Chech username already reggistered
        $query = "SELECT username, email FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username already registered!";
        }

        if (empty($errors)) {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Simpan ke database
            $query = "INSERT INTO users (email, username, password, no_telp) VALUES ('$email', '$username', '$hashedPassword', '$phone')";
            $queryIntoOnlineUsers = "INSERT INTO current_users_online (user_id) SELECT id FROM users WHERE username = '$username'";
            if (mysqli_query($conn, $query)) {
                if (mysqli_query($conn, $queryIntoOnlineUsers)) {
                    $success = "Registration successful! You can now log in.";
                    $_SESSION['success'] = $success;
                    mysqli_close($conn);
                    header('Location: ../login.php');
                    exit();
                }
            } else {
                $errors[] = "Error: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        }

        // Error handling
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ../register.php');
            exit();
        }
    }
}
