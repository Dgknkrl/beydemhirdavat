<?php
require_once 'db_connection.php';

if (isset($_GET['parent_id'])) {
    try {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT kategori_id, kategori_adi 
            FROM kategori 
            WHERE parent_id = ? 
            ORDER BY kategori_adi
        ");
        
        $stmt->execute([$_GET['parent_id']]);
        $altKategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($altKategoriler);
        
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Parent ID gerekli']);
} 