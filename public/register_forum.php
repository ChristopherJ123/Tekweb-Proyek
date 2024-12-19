<?php
session_start();
include "../src/db.php";
global $conn;

if (!isset($_SESSION['user_id'])) {
    $errors = [];
    $errors[] = "Silahkan login terlebih dahulu";
    $_SESSION['errors'] = $errors;
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasar Kaki Lima | Register Forum Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Gaya Umum */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #ff1500, #ff8000);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 0 1rem; /* Tambahkan jarak untuk samping */
            box-sizing: border-box;
            color: #fff;
        }
        h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            font-weight: bold;
        }
        form {
            width: 100%; /* Lebar penuh pada layar kecil */
            max-width: 400px; /* Batas lebar maksimum */
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            box-sizing: border-box; /* Pastikan padding dihitung dalam ukuran */
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
            width: 100%; /* Input sesuai dengan lebar kotak */
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #cb5811;
            box-shadow: 0 0 8px rgba(203, 51, 17, 0.3);
        }
        .btn-submit {
            display: inline-block;
            width: 100%; /* Tombol memenuhi lebar kotak */
            padding: 0.75rem;
            background: linear-gradient(135deg, #ff8000, #ff1500);
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
            background: linear-gradient(135deg, #ff1500, #ff8000);
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
        .back-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #fff;
            font-size: 1rem;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 5px;
            transition: background 0.3s;
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .back-button svg {
            height: 20px;
            width: 20px;
            fill: currentColor;
        }
        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Responsiveness */
        @media (max-width: 480px) {
            h2 {
                font-size: 1.5rem;
            }
            form {
                padding: 15px; /* Sesuaikan padding untuk layar kecil */
            }
            .form-group label {
                font-size: 0.9rem;
            }
            .form-group input {
                font-size: 0.9rem;
            }
            .btn-submit {
                font-size: 0.9rem;
                padding: 0.6rem;
            }
            .text-small {
                font-size: 0.75rem;
            }
            .back-button {
                font-size: 0.85rem;
                gap: 0.3rem;
            }
            .back-button svg {
                height: 16px;
                width: 16px;
            }
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
    <a href="javascript:history.back()" class="back-button">
        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24" fill="currentColor">
            <path d="M480 992 80 592l400-400 45 45-320 320h675v60H205l320 320-45 45Z"/>
        </svg>
        Back
    </a>

    <h2>Register Forum</h2>

    <form action="scripts/register_forum_script.php" method="post">
        Pilih produk yang mau di masukkan forum. Max 6.
        <div class="flex flex-col mb-3">
            <?php
            $userID = $_SESSION['user_id'];
            $queryProducts = "SELECT * FROM products WHERE author = '$userID'";
            $result = mysqli_query($conn, $queryProducts);
            foreach ($result as $product) { ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="<?=$product['id']?>" name="selectedProducts[]" id="product-<?=$product['id']?>">
                    <label class="form-check-label" for="product-<?=$product['id']?>">
                        <?=$product['name']?>
                    </label>
                </div>
            <?php }
            ?>
        </div>

        <div class="form-group">
            <label for="comment-id">Deskripsi</label>
            <input type="text" name="comment" id="comment-id" placeholder="Komentar" required>
        </div>

        <button type="submit" class="btn-submit">Buat</button>
    </form>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll(".form-check-input");
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", function() {
                const selectedCount = document.querySelectorAll(".form-check-input:checked").length;
                if (selectedCount > 6) {
                    Swal.fire({
                        title: "Error!",
                        text: "You can only select up to 6 products.",
                        icon: "error"
                    });
                    this.checked = false;
                }
            });
        });
    });
</script>

</body>
</html>
