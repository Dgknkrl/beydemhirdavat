<?php
require_once 'db_connection.php';

try {
    $db = Database::getInstance()->getConnection();
    
    $ana_kategori_id = $_GET['ana_kategori_id'] ?? null;
    
    if (!$ana_kategori_id) {
        throw new Exception('Ana kategori ID\'si belirtilmedi');
    }
    
    $query = $db->prepare("
        SELECT alt_kategori_id, alt_kategori_adi 
        FROM alt_kategori 
        WHERE ana_kategori_id = ? 
        ORDER BY alt_kategori_adi ASC
    ");
    $query->execute([$ana_kategori_id]);
    $altKategoriler = $query->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    
    echo json_encode($altKategoriler);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
} 