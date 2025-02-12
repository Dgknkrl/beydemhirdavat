<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance()->getConnection();
        $islem = $_POST['islem'] ?? '';
        $yorumId = $_POST['yorum_id'] ?? null;

        switch ($islem) {
            case 'durum_degistir':
                $yeniDurum = $_POST['yeni_durum'] ?? 0;
                $stmt = $db->prepare("UPDATE urun_yorumlari SET yayin_durumu = ? WHERE yorum_id = ?");
                $success = $stmt->execute([$yeniDurum, $yorumId]);
                echo json_encode(['success' => $success]);
                break;

            case 'sil':
                $stmt = $db->prepare("DELETE FROM urun_yorumlari WHERE yorum_id = ?");
                $success = $stmt->execute([$yorumId]);
                echo json_encode(['success' => $success]);
                break;

            default:
                throw new Exception('Geçersiz işlem');
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
} 