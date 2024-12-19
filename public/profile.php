<?php
session_start();
include "../src/db.php";

global $conn;

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, no_telp, bio, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Uncial+Antiqua&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>User Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 0px;
        }
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .profile-header {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background: #ff9f43;
            color: white;
        }
        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid white;
            margin-bottom: 10px;
        }
        .profile-header h1 {
            font-size: 24px;
        }
        .profile-header p {
            font-size: 14px;
        }
        .edit-btn {
            background: white;
            color: #ff9f43;
            border: 1px solid #ff9f43;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }
        .edit-btn:hover {
            background: #ffd79a;
        }
        .profile-body {
            padding: 20px;
        }
        .profile-section {
            margin-bottom: 20px;
        }
        .profile-section h2 {
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 2px solid #ff9f43;
            display: inline-block;
            padding-bottom: 5px;
        }
        .edit-section input, .edit-section textarea, .edit-section button {
            padding: 10px;
            font-size: 14px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .edit-section button {
            background: #ff9f43;
            color: white;
            border: none;
            cursor: pointer;
        }
        .edit-section button:hover {
            background: #e68a33;
        }
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.2s;
        }
        .popup.active {
            visibility: visible;
            opacity: 1;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .popup-content input {
            width: 80%;
            margin-bottom: 10px;
            padding: 8px;
        }
        .popup-content button {
            padding: 8px 16px;
            background-color: #ff9f43;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .popup-content button:hover {
            background-color: #e68a33;
        }
    </style>
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
if (isset($_SESSION['success'])) { ?>
    <script>
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

<!-- Top Nav Bar & Scripts -->
<?php include '../src/topnavbar.php' ?>

<div>
    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <img src="<?= htmlspecialchars($user_data['profile_picture'] ?: 'uploads/default_profile.png') ?>" alt="Profile Picture">
            <h1><?= htmlspecialchars($user_data['username']) ?></h1>
            <p><?= htmlspecialchars($user_data['bio']) ?></p>
            <button class="edit-btn" onclick="togglePopup()">Edit Profile Picture</button>
        </div>


        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Contact Information -->
            <div class="profile-section">
                <h2>Contact Information</h2>
                <p>Email: <?= htmlspecialchars($user_data['email']) ?></p>
                <p>Phone Number: <?= htmlspecialchars($user_data['no_telp']) ?></p>
                <button class="edit-btn">Edit Contact Info</button>
            </div>

                        <!-- Edit Section (Only for Owner) -->
            <div class="profile-section">
                <h2>Edit Profile</h2>   
                <form action="scripts/update_profile.php" method="post" class="edit-section">
                    <input type="text" name="username" placeholder="Edit Username" value="<?= htmlspecialchars($user_data['username']) ?>">
                    <textarea name="bio" placeholder="Edit Bio"><?= htmlspecialchars($user_data['bio']) ?></textarea>
                    <input type="text" name="no_telp" placeholder="Edit Phone Number" value="<?= htmlspecialchars($user_data['no_telp']) ?>">
                    <button type="submit">Save Changes</button>
                </form>
            </div>

        </div>
    </div>
</div>


<div class="popup" id="popup">
    <div class="popup-content">
        <h3>Change Profile Picture</h3>
        <form action="scripts/update_profile_picture.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_picture" accept="image/*">
            <button type="submit">Upload</button>
        </form>
        <button onclick="togglePopup()">Cancel</button>
    </div>
</div>

<script>
    function togglePopup() {
        const popup = document.getElementById('popup');
        popup.classList.toggle('active');
    }
</script>
</body>

</html>
