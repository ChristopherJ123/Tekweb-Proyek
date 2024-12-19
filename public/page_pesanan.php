<?php
include '../src/db.php';
global $conn;

session_start();
$user_id = $_SESSION['user_id'] ?? null;

// Proses pembatalan pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];

    if ($user_id && $transaction_id) {
        // Query untuk membatalkan pesanan
        $sql = "DELETE FROM TRANSACTIONS WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $transaction_id, $user_id);

        if ($stmt->execute()) {
            $success_message = "Pesanan berhasil dibatalkan.";
        } else {
            $error_message = "Gagal membatalkan pesanan. Silakan coba lagi.";
        }
        $stmt->close();
    } else {
        $error_message = "Permintaan tidak valid.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasar Kaki Lima | Pesanan Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-md w-full max-w-4xl">
        <h1 class="text-xl md:text-2xl font-bold text-center mb-4">List Pesanan</h1>

        <!-- Pesan sukses atau error -->
        <?php if (isset($success_message)): ?>
            <p class="text-center text-green-500 mb-4"><?= $success_message ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="text-center text-red-500 mb-4"><?= $error_message ?></p>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm text-sm md:text-base">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 text-left font-medium text-gray-700">#</th>
                        <th class="py-2 px-4 text-left font-medium text-gray-700">Nama Produk</th>
                        <th class="py-2 px-4 text-left font-medium text-gray-700">Jumlah</th>
                        <th class="py-2 px-4 text-left font-medium text-gray-700">Total Harga</th>
                        <th class="py-2 px-4 text-left font-medium text-gray-700">Tanggal Pesan</th>
                        <th class="py-2 px-4 text-left font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($user_id) {
                        //query untuk mengambil data transaksi user
                        $sql = "
                            SELECT 
                                t.id AS transaction_id,
                                ti.product_id,
                                p.name AS product_name,
                                ti.quantity,
                                ti.total_price,
                                t.created_at AS order_date
                            FROM 
                                TRANSACTIONS t
                            JOIN 
                                TRANSACTION_ITEMS ti ON t.id = ti.transaction_id
                            JOIN 
                                PRODUCTS p ON ti.product_id = p.id
                            WHERE 
                                t.user_id = ?
                            ORDER BY 
                                t.created_at DESC
                        ";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        //pesanan
                        if ($result->num_rows > 0) {
                            $index = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td class='py-2 px-4 text-sm text-gray-700'>{$index}</td>
                                    <td class='py-2 px-4 text-sm text-gray-700'>{$row['product_name']}</td>
                                    <td class='py-2 px-4 text-sm text-gray-700'>{$row['quantity']}</td>
                                    <td class='py-2 px-4 text-sm text-gray-700'>Rp " . number_format($row['total_price'], 0, ',', '.') . "</td>
                                    <td class='py-2 px-4 text-sm text-gray-700'>{$row['order_date']}</td>
                                    <td class='py-2 px-4 text-sm'>
                                        <form method='POST' onsubmit='return confirm(\"Apakah Anda yakin ingin membatalkan pesanan ini?\")'>
                                            <input type='hidden' name='transaction_id' value='{$row['transaction_id']}'>
                                            <button type='submit' class='bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600'>Batal</button>
                                        </form>
                                    </td>
                                </tr>";
                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-4 text-center text-gray-500'>Belum memiliki pesanan.</td></tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "<tr><td colspan='6' class='py-4 text-center text-gray-500'>Silakan login untuk melihat pesanan Anda.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
