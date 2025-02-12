<?php
require_once 'db_connection.php';

if (isset($_GET['id'])) {
    try {
        $db = Database::getInstance()->getConnection();
        
        $id = $_GET['id'];
        $stmt = $db->prepare("DELETE FROM iletisim_formu WHERE iletisim_id = ?");
        $result = $stmt->execute([$id]);
        
        if ($result) {
            header('Location: iletisim_kayitlari.php?success=1');
        } else {
            header('Location: iletisim_kayitlari.php?error=1');
        }
    } catch (Exception $e) {
        header('Location: iletisim_kayitlari.php?error=' . urlencode($e->getMessage()));
    }
    exit;
} 