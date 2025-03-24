<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance()->getConnection();
        $islem = $_POST['islem'] ?? '';
        $soruId = $_POST['soru_id'] ?? null;

        switch ($islem) {
            case 'durum_degistir':
                $yeniDurum = $_POST['yeni_durum'] ?? 0;
                $stmt = $db->prepare("UPDATE satici_sorulari SET yayin_durumu = ? WHERE soru_id = ?");
                $success = $stmt->execute([$yeniDurum, $soruId]);
                echo json_encode(['success' => $success]);
                break;

            case 'cevapla':
                $cevap = $_POST['cevap'] ?? '';
                $stmt = $db->prepare("UPDATE satici_sorulari SET admin_cevabi = ? WHERE soru_id = ?");
                $success = $stmt->execute([$cevap, $soruId]);
                echo json_encode(['success' => $success]);
                break;

            case 'sil':
                $stmt = $db->prepare("DELETE FROM satici_sorulari WHERE soru_id = ?");
                $success = $stmt->execute([$soruId]);
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