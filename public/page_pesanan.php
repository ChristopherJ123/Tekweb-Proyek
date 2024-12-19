<?php
include '../src/db.php';
global $conn;

session_start();
$user_id = $_SESSION['user_id'] ?? null;

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

    //kode untuk cek ada pesanan atau tidak
    if ($result->num_rows > 0) {
        echo "<tbody>";
        $index = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td class='py-2 px-4 text-sm text-gray-700'>{$index}</td>
                <td class='py-2 px-4 text-sm text-gray-700'>{$row['product_name']}</td>
                <td class='py-2 px-4 text-sm text-gray-700'>{$row['quantity']}</td>
                <td class='py-2 px-4 text-sm text-gray-700'>Rp " . number_format($row['total_price'], 0, ',', '.') . "</td>
                <td class='py-2 px-4 text-sm text-gray-700'>{$row['order_date']}</td>
                <td class='py-2 px-4 text-sm'>
                    <button class='bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600'>Batal</button>
                </td>
            </tr>";
            $index++;
        }
        echo "</tbody>";
    } else {
        echo "<p id='noOrdersMessage' class='text-center text-gray-500 mt-4'>Belum memiliki pesanan.</p>";
    }
    $stmt->close();
} else {
    echo "<p class='text-center text-gray-500 mt-4'>Silakan login untuk melihat pesanan Anda.</p>";
}
?>
