<?php
require_once 'db_connection.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Ana kategorileri çek
    $query = $db->query("SELECT ana_kategori_id, ana_kategori_adi FROM ana_kategori ORDER BY ana_kategori_adi");
    $kategoriler = $query->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($kategoriler);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 