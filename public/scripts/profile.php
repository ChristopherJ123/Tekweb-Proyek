<?php
session_start();
include "../src/db.php";
global $conn;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
            padding: 20px;
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
        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .product-item {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            width: calc(50% - 20px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product-item img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .product-item h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .product-item p {
            font-size: 14px;
        }
        .edit-section {
            display: flex;
            flex-direction: column;
            gap: 10px;
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
    </style>
</head>
<body>
    <div>
        <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <img src="https://via.placeholder.com/100" alt="Profile Picture">
            <h1>Username</h1>
            <p>This is the user bio. bisa diedit.</p>
            <button class="edit-btn">Edit Profile Picture</button>
        </div>

        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Contact Information -->
            <div class="profile-section">
                <h2>Contact Information</h2>
                <p>Phone Number: +62 123 456 789</p>
                <button class="edit-btn">Edit Phone Number</button>
            </div>

            <!-- Products Section -->
            <div class="profile-section">
                <h2>Products Posted</h2>
                <div class="product-list">
                    <div class="product-item">
                        <img src="https://via.placeholder.com/150" alt="Product Image">
                        <h3>Product Name 1</h3>
                        <p>Price: Rp50,000</p>
                    </div>
                    <div class="product-item">
                        <img src="https://via.placeholder.com/150" alt="Product Image">
                        <h3>Product Name 2</h3>
                        <p>Price: Rp30,000</p>
                    </div>
                </div>
            </div>

            <!-- Edit Section (Only for Owner) -->
            <div class="profile-section">
                <h2>Edit Profile</h2>
                <div class="edit-section">
                    <input type="text" placeholder="Edit Username">
                    <textarea placeholder="Edit Bio"></textarea>
                    <input type="text" placeholder="Edit Phone Number">
                    <button>Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
