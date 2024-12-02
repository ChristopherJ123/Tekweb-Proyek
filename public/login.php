<?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            font-weight: bold;
        }
        form {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            color: #333;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #555;
        }
        .form-group input {
            width: 95%;
            padding: 0.40rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }
        .form-group input:focus {
            outline: none;
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.3);
        }
        .btn-submit {
            display: inline-block;
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #ffffff;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            transform: scale(1.05);
        }
        .text-small {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        .text-small a {
            color: #ffffff;
            text-decoration: underline;
        }
        .text-small a:hover {
            color: #ffe259;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    if (isset($_SESSION['errors'])) { ?>
        <script>
            Swal.fire({
                title: "Error!",
                text: "<?= implode("<br>", $_SESSION['errors']) ?>",
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
                text: "<?= $_SESSION['success'] ?>",
                icon: "success"
            });
        </script>
        <?php
        unset($_SESSION['success']);
    }
    ?>

    <div class="container">
        <h2>Login</h2>
        <form action="scripts/login_user_script.php" method="post">
            <!-- Username -->
            <div class="form-group">
                <label for="usernameOrEmail-id">Username</label>
                <input type="text" name="usernameOrEmail" id="usernameOrEmail-id" placeholder="Enter your username or email" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password-id">Password</label>
                <input type="password" name="password" id="password-id" placeholder="Enter your password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">Login</button>
        </form>
        <div class="text-small">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>
