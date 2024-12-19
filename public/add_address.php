<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Address</title>
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
            padding: 0;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .header {
            text-align: center;
            background: #ff9f43;
            color: #fff;
            padding: 10px 0;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            font-size: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #ff9f43;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #e68a33;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Add New Address</h1>
        </div>
        <form action="scripts/add_address_script.php" method="POST">
            <div>
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div>
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" required></textarea>
            </div>
            <div>
                <label for="provinsi">Provinsi</label>
                <input type="text" id="provinsi" name="provinsi" required>
            </div>
            <div>
                <label for="kota">Kota</label>
                <input type="text" id="kota" name="kota" required>
            </div>
            <div>
                <label for="kecamatan">Kecamatan</label>
                <input type="text" id="kecamatan" name="kecamatan" required>
            </div>
            <div>
                <label for="kode_pos">Kode Pos</label>
                <input type="text" id="kode_pos" name="kode_pos" required>
            </div>
            <div>
                <label for="catatan">Catatan (Optional)</label>
                <textarea id="catatan" name="catatan" rows="2"></textarea>
            </div>
            <button type="submit">Save Address</button>
        </form>
    </div>
</body>
</html>
