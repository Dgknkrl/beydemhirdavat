<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if(isset($_GET['id'])) {
    try {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM kataloglar WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $katalog = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($katalog) {
            echo json_encode([
                'success' => true,
                'katalog' => $katalog
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Katalog bulunamadı'
            ]);
        }
    } catch(Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'ID parametresi eksik'
    ]);
} 