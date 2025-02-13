<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['katalog_id'])) {
    try {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();

        $katalog_id = $_POST['katalog_id'];
        $isim = $_POST['isim'];
        $kisa_aciklama = $_POST['kisa_aciklama'];
        $tarih = $_POST['tarih'];

        // Temel bilgileri güncelle
        $sql = "UPDATE kataloglar SET isim = ?, kisa_aciklama = ?, tarih = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$isim, $kisa_aciklama, $tarih, $katalog_id]);

        // Resim güncellemeleri
        for($i = 1; $i <= 3; $i++) {
            if(isset($_FILES["resim$i"]) && $_FILES["resim$i"]['size'] > 0) {
                $resimData = file_get_contents($_FILES["resim$i"]['tmp_name']);
                $sql = "UPDATE kataloglar SET resim$i = ? WHERE id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$resimData, $katalog_id]);
            }
        }

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Katalog başarıyla güncellendi']);

    } catch (Exception $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
} 