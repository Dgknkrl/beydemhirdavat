<?php
require_once '../includes/db_connection.php';

$ana_kategoriler = isset($_GET['ana_kategoriler']) ? explode(',', $_GET['ana_kategoriler']) : [];

$markalar_query = "SELECT DISTINCT marka_adi FROM urunler";
if (!empty($ana_kategoriler)) {
    $ana_kategoriler = array_map('intval', $ana_kategoriler);
    $markalar_query .= " WHERE ana_kategori_id IN (" . implode(',', $ana_kategoriler) . ")";
}

$markalar_result = mysqli_query($conn, $markalar_query);
$markalar = [];

while ($row = mysqli_fetch_assoc($markalar_result)) {
    $markalar[] = $row['marka_adi'];
}

header('Content-Type: application/json');
echo json_encode($markalar);

mysqli_close($conn); 