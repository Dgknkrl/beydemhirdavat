<?php
require_once 'db_connection.php';

// Sadece AJAX istekleri için JSON header'ı ekle
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Ürün ID'sini al
    $urun_id = isset($_GET['id']) ? $_GET['id'] : null;
    
    if (!$urun_id) {
        die("Ürün ID'si belirtilmedi!");
    }
    
    // Ürün bilgilerini çek
    $urunQuery = $db->prepare("
        SELECT u.*, ak.ana_kategori_adi, altk.alt_kategori_adi,
               r.resim1, r.resim2, r.resim3, r.resim4
        FROM urunler u
        LEFT JOIN ana_kategori ak ON u.ana_kategori_id = ak.ana_kategori_id
        LEFT JOIN alt_kategori altk ON u.alt_kategori_id = altk.alt_kategori_id
        LEFT JOIN resimler r ON u.urun_id = r.urun_id
        WHERE u.urun_id = ?
    ");
    $urunQuery->execute([$urun_id]);
    $urun = $urunQuery->fetch(PDO::FETCH_ASSOC);
    
    if (!$urun) {
        die("Ürün bulunamadı!");
    }
    
    // Ürün özelliklerini çek
    $ozellikQuery = $db->prepare("
        SELECT ozellik_id, ozellik_adi, ozellik_deger 
        FROM urun_ozellik 
        WHERE urun_id = ?
        ORDER BY ozellik_adi ASC
    ");
    $ozellikQuery->execute([$urun_id]);
    $ozellikler = $ozellikQuery->fetchAll(PDO::FETCH_ASSOC);

    // POST işlemleri için AJAX kontrolü ekleyelim
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $islem = $_POST['islem'] ?? '';
        
        // AJAX istekleri için
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            
            switch($islem) {
                case 'ozellik_guncelle':
                    try {
                        $ozellikId = $_POST['ozellik_id'];
                        $yeniDeger = $_POST['yeni_deger'];
                        
                        $stmt = $db->prepare("UPDATE urun_ozellik SET ozellik_deger = ? WHERE ozellik_id = ? AND urun_id = ?");
                        $result = $stmt->execute([$yeniDeger, $ozellikId, $urun_id]);
                        
                        echo json_encode(['success' => $result]);
                        exit;
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                        exit;
                    }
                    break;

                case 'ozellik_sil':
                    try {
                        $ozellikId = $_POST['ozellik_id'];
                        
                        $stmt = $db->prepare("DELETE FROM urun_ozellik WHERE ozellik_id = ? AND urun_id = ?");
                        $result = $stmt->execute([$ozellikId, $urun_id]);
                        
                        echo json_encode(['success' => $result]);
                        exit;
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                        exit;
                    }
                    break;

                case 'resim_guncelle':
                    if (isset($_FILES['resim'])) {
                        $resimNo = $_POST['resim_no'];
                        $resimData = file_get_contents($_FILES['resim']['tmp_name']);
                        
                        $updateResim = $db->prepare("
                            UPDATE resimler 
                            SET resim$resimNo = ? 
        WHERE urun_id = ?
                        ");
                        $updateResim->execute([$resimData, $urun_id]);
                    }
                    break;

                case 'ozellik_ekle':
                    try {
                        $ozellikAdi = $_POST['ozellik_adi'];
                        $ozellikDeger = $_POST['ozellik_deger'];
                        
                        $insertOzellik = $db->prepare("
                            INSERT INTO urun_ozellik (urun_id, ozellik_adi, ozellik_deger)
                            VALUES (?, ?, ?)
                        ");
                        $result = $insertOzellik->execute([$urun_id, $ozellikAdi, $ozellikDeger]);
                        
                        if ($result) {
                            echo json_encode([
                                'success' => true,
                                'ozellik_id' => $db->lastInsertId() // Yeni eklenen özelliğin ID'sini dön
                            ]);
                        } else {
                            echo json_encode([
                                'success' => false,
                                'error' => 'Özellik eklenemedi'
                            ]);
                        }
                    } catch (Exception $e) {
                        echo json_encode([
                            'success' => false,
                            'error' => $e->getMessage()
                        ]);
                    }
                    exit;
                    break;

                case 'temel_bilgiler':
                    try {
                        $field = $_POST['field'];
                        $value = $_POST['value'];
                        
                        // Güvenli alanları belirle
                        $allowedFields = ['urun_id', 'urun_adi', 'marka_adi', 'ana_kategori_id', 'alt_kategori_id'];
                        
                        if (!in_array($field, $allowedFields)) {
                            throw new Exception('Geçersiz alan');
                        }
                        
                        // Eğer ürün ID'si değiştiriliyorsa
                        if ($field === 'urun_id') {
                            // Transaction başlat
                            $db->beginTransaction();
                            
                            try {
                                // Önce ilişkili tabloları güncelle
                                $tables = ['resimler', 'urun_ozellik'];
                                foreach ($tables as $table) {
                                    $updateRelated = $db->prepare("UPDATE $table SET urun_id = ? WHERE urun_id = ?");
                                    $updateRelated->execute([$value, $urun_id]);
                                }
                                
                                // Sonra ürünü güncelle
                                $updateQuery = $db->prepare("UPDATE urunler SET urun_id = ? WHERE urun_id = ?");
                                $result = $updateQuery->execute([$value, $urun_id]);
                                
                                $db->commit();
                                echo json_encode(['success' => true]);
                            } catch (Exception $e) {
                                $db->rollBack();
                                throw new Exception('Güncelleme başarısız: ' . $e->getMessage());
                            }
                        } else {
                            // Diğer alanlar için normal güncelleme
                            $updateQuery = $db->prepare("UPDATE urunler SET $field = ? WHERE urun_id = ?");
                            $result = $updateQuery->execute([$value, $urun_id]);
                            
                            if ($result) {
                                echo json_encode(['success' => true]);
                            } else {
                                throw new Exception('Güncelleme başarısız');
                            }
                        }
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                    }
                    exit;
                    break;

                case 'kategori_guncelle':
                    try {
                        $type = $_POST['type'];
                        $value = $_POST['value'];
                        
                        if ($type === 'ana_kategori') {
                            $updateQuery = $db->prepare("
                                UPDATE urunler 
                                SET ana_kategori_id = ?, 
                                    alt_kategori_id = NULL 
        WHERE urun_id = ?
                            ");
                            $result = $updateQuery->execute([$value, $urun_id]);
                            
                            // Kategori adını al
                            $kategoriQuery = $db->prepare("SELECT ana_kategori_adi FROM ana_kategori WHERE ana_kategori_id = ?");
                            $kategoriQuery->execute([$value]);
                            $kategoriAdi = $kategoriQuery->fetchColumn();
                            
                        } else if ($type === 'alt_kategori') {
                            $updateQuery = $db->prepare("
                                UPDATE urunler 
                                SET alt_kategori_id = ? 
                                WHERE urun_id = ?
                            ");
                            $result = $updateQuery->execute([$value, $urun_id]);
                            
                            // Kategori adını al
                            $kategoriQuery = $db->prepare("SELECT alt_kategori_adi FROM alt_kategori WHERE alt_kategori_id = ?");
                            $kategoriQuery->execute([$value]);
                            $kategoriAdi = $kategoriQuery->fetchColumn();
                        }
                        
                        if ($result) {
                            echo json_encode([
                                'success' => true,
                                'kategori_adi' => $kategoriAdi
                            ]);
                        } else {
                            throw new Exception('Kategori güncellenemedi');
                        }
} catch (Exception $e) {
                        echo json_encode([
                            'success' => false,
                            'error' => $e->getMessage()
                        ]);
                    }
                    exit;
                    break;
            }
        }
        // Normal form gönderimi için
        else {
            switch($islem) {
                case 'temel_bilgiler':
                    $updateQuery = $db->prepare("
                        UPDATE urunler SET 
                        urun_adi = ?,
                        marka_adi = ?,
                        ana_kategori_id = ?,
                        alt_kategori_id = ?
                        WHERE urun_id = ?
                    ");
                    $updateQuery->execute([
                        $_POST['urun_adi'],
                        $_POST['marka_adi'],
                        $_POST['ana_kategori_id'],
                        $_POST['alt_kategori_id'],
                        $urun_id
                    ]);
                    break;
                
                case 'resim_guncelle':
                    if (isset($_FILES['resim'])) {
                        $resimNo = $_POST['resim_no'];
                        $resimData = file_get_contents($_FILES['resim']['tmp_name']);
                        
                        $updateResim = $db->prepare("
                            UPDATE resimler 
                            SET resim$resimNo = ? 
                            WHERE urun_id = ?
                        ");
                        $updateResim->execute([$resimData, $urun_id]);
                    }
                    break;

                case 'ozellik_ekle':
                    $ozellikAdi = $_POST['ozellik_adi'];
                    $ozellikDeger = $_POST['ozellik_deger'];
                    
                    $insertOzellik = $db->prepare("
                        INSERT INTO urun_ozellik (urun_id, ozellik_adi, ozellik_deger)
                        VALUES (?, ?, ?)
                    ");
                    $insertOzellik->execute([$urun_id, $ozellikAdi, $ozellikDeger]);
                    break;
            }
            
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $urun_id);
            exit;
        }
    }
    
} catch (Exception $e) {
    die("Bir hata oluştu: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Düzenle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Temel Stiller */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            display: flex;
            min-height: calc(100vh - 40px);
            gap: 20px;
            position: relative;
        }
        
        .sidebar {
            width: 280px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px 0;
            position: fixed;
            top: 20px;
            bottom: 20px;
            overflow-y: auto;
        }

        .panel-title {
            font-size: 18px;
            color: #333;
            padding: 0 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .menu-list {
            list-style: none;
            padding: 15px;
            margin: 0;
        }

        .menu-item {
            padding: 12px 20px;
            color: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        
        .menu-item:hover {
            background-color: #f0f0f0;
            color: #333;
        }
        
        .menu-item.active {
            background-color: #ff6b00;
            color: white !important;
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
        }


        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        /* Ana İçerik */
        .main-content {
            margin-left: 300px; /* sidebar width + gap */
            width: calc(100% - 300px);
            display: flex;
            flex-direction: column;
            gap: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            overflow: hidden;
        }

        .ust-bolum {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .sol-bolum {
            flex: 1;
            min-width: 300px;
            max-width: 400px;
        }
        
        .sag-bolum {
            flex: 1;
            min-width: 400px;
        }
        
        .bilgi-satir {
            background: white;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 10px;
        }

        .bilgi-label {
            color: #333;
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .bilgi-deger {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
        }

        /* Resimler Grid */
        .resim-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
            width: 100%;
        }

        .resim-kutu {
            width: 100%;
            aspect-ratio: 1;
            min-width: 150px;
            max-width: 200px;
            border-radius: 4px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-direction: column;
            gap: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .resim-kutu img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }
        
        .resim-butonlar {
            display: flex;
            gap: 5px;
        }

        /* Özellikler */
        .ozellikler {
            width: 100%;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .yeni-ozellik {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .yeni-ozellik input {
            height: 30px;
            padding: 0 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 15px;
        }

        .yeni-ozellik input:first-child {
            width: 120px;
        }

        .ozellik-satir {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 8px 10px;
            border-radius: 4px;
            margin-bottom: 6px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .ozellik-icerik {
            display: flex;
            gap: 20px;
        }

        .ozellik-adi {
        min-width: 150px;
        max-width: 300px;
            color: #333;
            font-size: 15px;
        }

        .ozellik-deger {
            color: #666;
            font-size: 15px;
        }

        .ozellik-butonlar{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
            gap: 10px;
        }
        /* Butonlar */
        .ekle-btn, .btn-primary {
            background: #ff6b00;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
        }

        .edit-btn, .delete-btn {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
        }

        .edit-btn i,
        .delete-btn i {
            color: #003366;
            font-size: 20px;
        }

        /* Alt Butonlar */
        .alt-butonlar {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        /* Modal stilleri ekleyelim - style tag'i içine */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .yorum-sorular-container {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            width: 100%;
            overflow: hidden;
        }

        .yorumlar-bolumu, .sorular-bolumu {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .yorum-baslik-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
            margin-bottom: 20px;
        }

        .yorum-sayisi {
            background: #f8f9fa;
            padding: 5px 15px;
            border-radius: 20px;
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .yorum-kutusu {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .yorum-ust {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .yorum-kullanici {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .kullanici-avatar {
            width: 40px;
            height: 40px;
            background: #f0f0f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .kullanici-avatar i {
            color: #666;
            font-size: 20px;
        }
        
        .kullanici-bilgi {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .kullanici-adi {
            color: #333;
            font-size: 16px;
        }
        
        .kullanici-eposta {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .kullanici-eposta:hover {
            color: #ff6b00;
        }
        
        .yorum-durum {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .yorum-durum.yayinda {
            background-color: #dcf5dc;
            color: #2e7d32;
            border: 1px solid #2e7d32;
        }
        
        .yorum-durum.beklemede {
            background-color: #fff3e0;
            color: #ef6c00;
            border: 1px solid #ef6c00;
        }
        
        .yorum-durum:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .yorum-icerik {
            margin: 15px 0;
            line-height: 1.6;
            color: #444;
            position: relative;
            padding-left: 20px;
        }

        .yorum-icerik::before {
            position: absolute;
            left: 20px;
            color: #666;
            font-weight: 500;
            font-size: 14px;
        }

        .yorum-icerik-metin {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 25px;
            border-left: 3px solid #ff6b00;
        }

        .admin-cevap {
            background: #e8f4ff;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 10px 0 10px 20px;
            border-left: 3px solid #ff6b00;
        }

        .admin-cevap.bekliyor {
            background: #fff3e0;
            border-left: 3px solid #ef6c00;
        }

        .admin-cevap.bekliyor strong {
            color: #ef6c00;
        }

        .admin-cevap strong {
            color: #003366;
            display: block;
            margin-bottom: 8px;
        }

        .yorum-alt, .soru-alt {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9em;
            color: #888;
        }

        .yorum-islemler, .soru-islemler {
            display: flex;
            gap: 10px;
        }
        
        .islem-btn {
            background: none;
            border: none;
            color: #003366;
            cursor: pointer;
            padding: 5px;
        }

        .islem-btn:hover {
            color: #ff6b00;
        }

        /* Soru stilleri */
        .soru-kutusu {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .soru-ust {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .soru-kullanici {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .soru-icerik-metin {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 10px;
            border-left: 3px solid #003366;
        }

        .admin-cevap {
            background: #e8f4ff;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 10px 0 10px 20px;
            border-left: 3px solid #ff6b00;
        }

        .admin-cevap.bekliyor {
            background: #fff3e0;
            border-left: 3px solid #ef6c00;
        }

        .admin-cevap.bekliyor strong {
            color: #ef6c00;
        }

        .admin-cevap strong {
            color: #003366;
            display: block;
            margin-bottom: 8px;
        }

        .soru-durum {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .soru-durum.yayinda {
            background-color: #dcf5dc;
            color: #2e7d32;
            border: 1px solid #2e7d32;
        }

        .soru-durum.beklemede {
            background-color: #fff3e0;
            color: #ef6c00;
            border: 1px solid #ef6c00;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sol Menü -->
        <div class="sidebar">
            <h2 class="panel-title">PANEL AYARLARI</h2>
            <ul class="menu-list">
                <li class="menu-group">
                    <a href="urun_ayarlari.php" class="menu-item active">
                        <i class="fas fa-box"></i>
                        Ürün Ayarları
                    </a>
                    <ul class="submenu">
                        <li><a href="urun_ayarlari.php" class="menu-item">Ürünleri Görüntüle</a></li>
                        <li><a href="urun_ekle.php" class="menu-item">Ürün Ekle</a></li>
                    </ul>
                </li>
                <a href="kategori_ayarlari.php" class="menu-item">
                    <i class="fas fa-tags"></i>
                    Kategori Ayarları
                </a>
                <a href="iletisim_kayitlari.php" class="menu-item">
                    <i class="fas fa-address-book"></i>
                    İletişim Kayıtları
                </a>
            </ul>
        </div>

        <!-- Ana İçerik -->
        <div class="main-content">
            <div class="ust-bolum">
                <div class="sol-bolum">
                    <div class="temel-bilgiler">
                        <!-- Ürün Numarası -->
                        <div class="bilgi-satir">
                            <span class="bilgi-label">Ürün Numarası</span>
                            <div class="bilgi-deger">
                                <span><?php echo $urun['urun_id']; ?></span>
                            </div>
                        </div>
                        
                        <!-- Ürün Adı -->
                        <div class="bilgi-satir">
                            <span class="bilgi-label">Ürün Adı</span>
                            <div class="bilgi-deger" data-type="urun_adi">
                                <span><?php echo $urun['urun_adi'] ?? ''; ?></span>
                                <button class="edit-btn" data-type="urun_adi">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Marka Adı -->
                        <div class="bilgi-satir">
                            <span class="bilgi-label">Marka Adı</span>
                            <div class="bilgi-deger" data-type="marka_adi">
                                <span><?php echo $urun['marka_adi'] ?? ''; ?></span>
                                <button class="edit-btn" data-type="marka_adi">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Ana Kategori -->
                        <div class="bilgi-satir">
                            <span class="bilgi-label">Ana Kategori Adı</span>
                            <div class="bilgi-deger" data-type="ana_kategori">
                                <span><?php echo $urun['ana_kategori_adi'] ?? ''; ?></span>
                                <button class="edit-btn" data-type="ana_kategori">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Alt Kategori -->
                        <div class="bilgi-satir" data-type="alt_kategori" style="display: <?php echo !empty($urun['alt_kategori_adi']) ? 'block' : 'none'; ?>">
                            <span class="bilgi-label">Alt Kategori Adı</span>
                            <div class="bilgi-deger" data-type="alt_kategori">
                                <span><?php echo $urun['alt_kategori_adi'] ?? 'Seçiniz'; ?></span>
                                <button class="edit-btn" data-type="alt_kategori">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sag-bolum">
                    <!-- Ürün Resimleri -->
                    <div class="resim-grid">
                    <?php for($i = 1; $i <= 4; $i++): ?>
                            <div class="resim-kutu">
                            <?php if(!empty($urun["resim$i"])): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($urun["resim$i"]); ?>" 
                                         alt="Ürün resmi <?php echo $i; ?>">
                            <?php endif; ?>
                                <div class="resim-butonlar">
                                    <button class="edit-btn" data-resim="<?php echo $i; ?>">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <button class="delete-btn" data-resim="<?php echo $i; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    
                    <!-- Özellikler -->
                        <div class="ozellikler">
                            <div class="yeni-ozellik">
                                <input type="text" id="yeniOzellikAdi" placeholder="Özellik Adı">
                                <input type="text" id="yeniOzellikDeger" placeholder="Özellik Değeri">
                                <button type="button" class="ekle-btn">Ekle</button>
                            </div>

                        <?php foreach($ozellikler as $ozellik): ?>
                                <div class="ozellik-satir" data-id="<?php echo $ozellik['ozellik_id']; ?>">
                                    <div class="ozellik-icerik">
                                        <span class="ozellik-adi"><?php echo htmlspecialchars($ozellik['ozellik_adi']); ?></span>
                                        <span class="ozellik-deger"><?php echo htmlspecialchars($ozellik['ozellik_deger']); ?></span>
                                    </div>
                                    <div class="ozellik-butonlar">
                                        <button class="edit-btn" onclick="ozellikDuzenle(<?php echo $ozellik['ozellik_id']; ?>)">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <button class="delete-btn" onclick="ozellikSil(<?php echo $ozellik['ozellik_id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Değişiklikleri Uygula -->
                    <div class="alt-butonlar">
                        <button class="btn btn-primary" onclick="degisiklikleriUygula()">Değişiklikleri Uygula</button>
                    </div>
                </div>
            </div>
            <div class="yorum-sorular-container">
                <!-- Yorumlar Bölümü -->
                <div class="yorumlar-bolumu">
                    <?php
                    // Ürün yorumlarını çek
                    $yorumlarQuery = $db->prepare("
                        SELECT uy.*, CONCAT(ad, ' ', soyad) as kullanici_adi, yayin_durumu
                        FROM urun_yorumlari uy
                        WHERE urun_id = ?
                        ORDER BY olusturma_tarihi DESC
                    ");
                    $yorumlarQuery->execute([$urun_id]);
                    $yorumlar = $yorumlarQuery->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <div class="yorum-baslik-container">
                        <h3>ÜRÜN YORUMLARI</h3>
                        <span class="yorum-sayisi"><?php echo count($yorumlar); ?> Yorum</span>
                    </div>
                    <?php
                    
                    foreach($yorumlar as $yorum): ?>
                        <div class="yorum-kutusu" data-id="<?php echo $yorum['yorum_id']; ?>">
                            <div class="yorum-ust">
                                <div class="yorum-kullanici">
                                    <div class="kullanici-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="kullanici-bilgi">
                                        <strong class="kullanici-adi"><?php echo htmlspecialchars($yorum['ad'] . ' ' . $yorum['soyad']); ?></strong>
                                        <a href="mailto:<?php echo htmlspecialchars($yorum['eposta']); ?>" class="kullanici-eposta">
                                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($yorum['eposta']); ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="yorum-durum <?php echo $yorum['yayin_durumu'] ? 'yayinda' : 'beklemede'; ?>">
                                    <?php echo $yorum['yayin_durumu'] ? 'Yayında' : 'Beklemede'; ?>
                                </div>
                            </div>
                            <div class="yorum-icerik">
                                <div class="yorum-icerik-metin">
                                    <?php echo htmlspecialchars($yorum['yorum']); ?>
                                </div>
                            </div>
                            <div class="yorum-alt">
                                <span class="tarih"><?php echo date('d.m.Y H:i', strtotime($yorum['olusturma_tarihi'])); ?></span>
                                <div class="yorum-islemler">
                                    <button class="islem-btn" onclick="yorumDurumDegistir(<?php echo $yorum['yorum_id']; ?>, <?php echo $yorum['yayin_durumu'] ? 0 : 1; ?>)">
                                        <i class="fas <?php echo $yorum['yayin_durumu'] ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                                    </button>
                                    <button class="islem-btn" onclick="yorumSil(<?php echo $yorum['yorum_id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Satıcı Soruları Bölümü -->
                <div class="sorular-bolumu">
                    <?php
                    // Satıcı sorularını çek
                    $sorularQuery = $db->prepare("
                        SELECT ss.*, CONCAT(ad, ' ', soyad) as kullanici_adi, 
                               yayin_durumu, isim_gorunsun_mu, admin_cevabi
                        FROM satici_sorulari ss
                        WHERE urun_id = ?
                        ORDER BY olusturma_tarihi DESC
                    ");
                    $sorularQuery->execute([$urun_id]);
                    $sorular = $sorularQuery->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <div class="yorum-baslik-container">
                        <h3>ÜRÜN SORULARI</h3>
                        <span class="yorum-sayisi"><?php echo count($sorular); ?> Soru</span>
                    </div>
                    <?php
                    
                    foreach($sorular as $soru): ?>
                        <div class="soru-kutusu" data-id="<?php echo $soru['soru_id']; ?>">
                            <div class="soru-ust">
                                <div class="soru-kullanici">
                                    <div class="kullanici-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="kullanici-bilgi">
                                        <strong class="kullanici-adi">
                                            <?php echo $soru['isim_gorunsun_mu'] ? 
                                                htmlspecialchars($soru['ad'] . ' ' . $soru['soyad']) : 
                                                'Misafir'; ?>
                                    </div>
                                </div>
                                <div class="soru-durum <?php echo $soru['yayin_durumu'] ? 'yayinda' : 'beklemede'; ?>">
                                    <?php echo $soru['yayin_durumu'] ? 'Yayında' : 'Beklemede'; ?>
                                </div>
                            </div>
                            <div class="soru-icerik-metin">
                                <?php echo htmlspecialchars($soru['soru']); ?>
                            </div>
                            <?php if(!empty($soru['admin_cevabi'])): ?>
                                <div class="admin-cevap <?php echo $soru['admin_cevabi'] === 'Cevap bekleniyor' ? 'bekliyor' : ''; ?>">
                                    <strong>Admin Cevabı:</strong>
                                    <?php echo htmlspecialchars($soru['admin_cevabi']); ?>
                                </div>
                            <?php endif; ?>
                            <div class="soru-alt">
                                <span class="tarih"><?php echo date('d.m.Y H:i', strtotime($soru['olusturma_tarihi'])); ?></span>
                                <div class="soru-islemler">
                                    <button class="islem-btn" onclick="soruDurumDegistir(<?php echo $soru['soru_id']; ?>, <?php echo $soru['yayin_durumu'] ? 0 : 1; ?>)">
                                        <i class="fas <?php echo $soru['yayin_durumu'] ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                                    </button>
                                    <button class="islem-btn" onclick="soruCevapla(<?php echo $soru['soru_id']; ?>)">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                    <button class="islem-btn" onclick="soruSil(<?php echo $soru['soru_id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal açma fonksiyonu
        function openModal(title, content) {
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>${title}</h3>
                        <button type="button" class="close-btn" onclick="closeModal(this)">&times;</button>
                    </div>
                    <div class="modal-body">
                        ${content}
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            return modal;
        }

        // Modal kapatma
        function closeModal(element) {
            const modal = element.closest('.modal');
            if (modal) modal.remove();
        }

        // Özellik düzenleme
        function ozellikDuzenle(ozellikId) {
            const satir = document.querySelector(`.ozellik-satir[data-id="${ozellikId}"]`);
            const deger = satir.querySelector('.ozellik-deger').textContent.trim();
            const ad = satir.querySelector('.ozellik-adi').textContent.trim();

            openModal(`${ad} Düzenle`, `
                <form onsubmit="return guncelleOzellik(event, ${ozellikId})">
                    <input type="text" name="yeni_deger" value="${deger}" class="form-control" required>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal(this)">İptal</button>
                    </div>
                </form>
            `);
        }

        // Özellik güncelleme
        async function guncelleOzellik(event, ozellikId) {
            event.preventDefault();
            const form = event.target;
            const yeniDeger = form.querySelector('input[name="yeni_deger"]').value;

            try {
                const formData = new FormData();
                formData.append('islem', 'ozellik_guncelle');
                formData.append('ozellik_id', ozellikId);
                formData.append('yeni_deger', yeniDeger);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    const satir = document.querySelector(`.ozellik-satir[data-id="${ozellikId}"]`);
                    satir.querySelector('.ozellik-deger').textContent = yeniDeger;
                    closeModal(form);
                } else {
                    throw new Error(data.error || 'Güncelleme başarısız');
                }
            } catch (error) {
                alert('Hata: ' + error.message);
            }
            return false;
        }

        // Özellik silme
        async function ozellikSil(ozellikId) {
            if (!confirm('Bu özelliği silmek istediğinize emin misiniz?')) return;

            try {
                const formData = new FormData();
                formData.append('islem', 'ozellik_sil');
                formData.append('ozellik_id', ozellikId);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    const satir = document.querySelector(`.ozellik-satir[data-id="${ozellikId}"]`);
                    satir.remove();
                } else {
                    throw new Error(data.error || 'Silme işlemi başarısız');
                }
            } catch (error) {
                alert('Hata: ' + error.message);
            }
        }

        // Yeni özellik ekleme
        document.querySelector('.ekle-btn').addEventListener('click', async function() {
            const adInput = document.getElementById('yeniOzellikAdi');
            const degerInput = document.getElementById('yeniOzellikDeger');
            const ad = adInput.value.trim();
            const deger = degerInput.value.trim();

            if (!ad || !deger) {
                alert('Lütfen özellik adı ve değerini giriniz');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('islem', 'ozellik_ekle');
                formData.append('ozellik_adi', ad);
                formData.append('ozellik_deger', deger);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    // Yeni özelliği UI'a ekle
                    const ozelliklerContainer = document.querySelector('.ozellikler');
                    const yeniSatir = document.createElement('div');
                    yeniSatir.className = 'ozellik-satir';
                    yeniSatir.dataset.id = data.ozellik_id; // Backend'den dönen yeni özellik ID'si
                    yeniSatir.innerHTML = `
                        <div class="ozellik-icerik">
                            <span class="ozellik-adi">${ad}</span>
                            <span class="ozellik-deger">${deger}</span>
                        </div>
                        <div class="ozellik-butonlar">
                            <button class="edit-btn" onclick="ozellikDuzenle(${data.ozellik_id})">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button class="delete-btn" onclick="ozellikSil(${data.ozellik_id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                    ozelliklerContainer.appendChild(yeniSatir);

                    // Input alanlarını temizle
                    adInput.value = '';
                    degerInput.value = '';
                } else {
                    throw new Error(data.error || 'Ekleme işlemi başarısız');
                }
            } catch (error) {
                alert('Hata: ' + error.message);
            }
        });

        // Resim işlemleri
        document.querySelectorAll('.edit-btn[data-resim]').forEach(btn => {
            btn.addEventListener('click', function() {
                const resimNo = this.dataset.resim;
                openModal(`Resim ${resimNo} Düzenle`, `
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="islem" value="resim_guncelle">
                        <input type="hidden" name="resim_no" value="${resimNo}">
                        <input type="file" name="resim" accept="image/*" class="form-control" required>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                            <button type="button" class="btn btn-secondary" onclick="closeModal(this)">İptal</button>
                        </div>
                    </form>
                `);
            });
        });
        
        // Resim silme
        document.querySelectorAll('.delete-btn[data-resim]').forEach(btn => {
            btn.addEventListener('click', async function() {
                if (!confirm('Bu resmi silmek istediğinize emin misiniz?')) return;

                const resimNo = this.dataset.resim;
                const formData = new FormData();
                formData.append('islem', 'resim_sil');
                formData.append('resim_no', resimNo);

                try {
                    const response = await fetch(window.location.href, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        throw new Error(data.error || 'Silme işlemi başarısız');
                    }
                } catch (error) {
                    alert('Hata: ' + error.message);
                }
            });
        });

        // Alt kategorileri getirme
        async function getAltKategoriler(anaKategoriId) {
            try {
                const response = await fetch(`get_alt_kategoriler.php?ana_kategori_id=${anaKategoriId}`);
                const altKategoriler = await response.json();
                
                const container = document.getElementById('altKategoriContainer');
                if(altKategoriler.length > 0) {
                    container.innerHTML = `
                        <select name="alt_kategori_id" class="form-control" required>
                            <option value="">Alt Kategori Seçiniz</option>
                            ${altKategoriler.map(kat => `
                                <option value="${kat.alt_kategori_id}">
                                    ${kat.alt_kategori_adi}
                                </option>
                            `).join('')}
                        </select>
                    `;
                }
            } catch (error) {
                alert('Alt kategoriler yüklenirken bir hata oluştu');
            }
        }

        // Değişiklikleri uygula
        function degisiklikleriUygula() {
            const formData = new FormData();
            formData.append('islem', 'degisiklikleri_uygula');
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if(!response.ok) throw new Error('İşlem başarısız');
                alert('Değişiklikler başarıyla kaydedildi');
                window.location.reload();
            })
            .catch(error => alert('Bir hata oluştu: ' + error.message));
        }

        // Kategori düzenleme butonları için event listener
        document.querySelectorAll('.bilgi-deger .edit-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const type = this.dataset.type;
                const currentValue = this.parentElement.querySelector('span').textContent.trim();

                // Ürün adı düzenleme
                if (type === 'urun_adi') {
                    const content = `
                        <form onsubmit="return updateField(event, 'urun_adi')">
                            <input type="text" name="value" value="${currentValue}" class="form-control" required>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Kaydet</button>
                                <button type="button" class="btn btn-secondary" onclick="closeModal(this)">İptal</button>
                            </div>
                        </form>
                    `;
                    openModal('ÜRÜN ADI Düzenle', content);
                }
                // Marka adı düzenleme
                else if (type === 'marka_adi') {
                    const content = `
                        <form onsubmit="return updateField(event, 'marka_adi')">
                            <input type="text" name="value" value="${currentValue}" class="form-control" required>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Kaydet</button>
                                <button type="button" class="btn btn-secondary" onclick="closeModal(this)">İptal</button>
                            </div>
                        </form>
                    `;
                    openModal('MARKA ADI Düzenle', content);
                }
                // Ana kategori düzenleme
                else if (type === 'ana_kategori') {
                    try {
                        // Ana kategorileri getir
                        const response = await fetch('get_kategoriler.php');
                        const kategoriler = await response.json();
                        
                        const content = `
                            <form onsubmit="return updateKategori(event, 'ana_kategori')">
                                <select name="value" class="form-control" required>
                                    <option value="">Ana Kategori Seçiniz</option>
                                    ${kategoriler.map(kat => `
                                        <option value="${kat.ana_kategori_id}" ${currentValue === kat.ana_kategori_adi ? 'selected' : ''}>
                                            ${kat.ana_kategori_adi}
                                        </option>
                                    `).join('')}
                                </select>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                    <button type="button" class="btn btn-secondary" onclick="closeModal(this)">İptal</button>
                                </div>
                            </form>
                        `;
                        openModal('ANA KATEGORİ Düzenle', content);
                    } catch (error) {
                        alert('Kategoriler yüklenirken hata oluştu: ' + error.message);
                    }
                }
                // Alt kategori düzenleme
                else if (type === 'alt_kategori') {
                    const anaKategoriSpan = document.querySelector('.bilgi-deger[data-type="ana_kategori"] span');
                    if (!anaKategoriSpan || anaKategoriSpan.textContent.trim() === '') {
                        alert('Önce ana kategori seçmelisiniz!');
                        return;
                    }

                    try {
                        // Ana kategori ID'sini al
                        const anaKategoriResponse = await fetch('get_kategoriler.php');
                        const anaKategoriler = await anaKategoriResponse.json();
                        const anaKategori = anaKategoriler.find(k => k.ana_kategori_adi === anaKategoriSpan.textContent.trim());
                        
                        if (!anaKategori) {
                            throw new Error('Ana kategori bulunamadı');
                        }

                        // Alt kategorileri getir
                        const altKategoriResponse = await fetch(`get_alt_kategoriler.php?ana_kategori_id=${anaKategori.ana_kategori_id}`);
                        const altKategoriler = await altKategoriResponse.json();
                        
                        const content = `
                            <form onsubmit="return updateKategori(event, 'alt_kategori')">
                                <select name="value" class="form-control" required>
                                    <option value="">Alt Kategori Seçiniz</option>
                                    ${altKategoriler.map(kat => `
                                        <option value="${kat.alt_kategori_id}" ${currentValue === kat.alt_kategori_adi ? 'selected' : ''}>
                                            ${kat.alt_kategori_adi}
                                        </option>
                                    `).join('')}
                                </select>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                    <button type="button" class="btn btn-secondary" onclick="closeModal(this)">İptal</button>
                                </div>
                            </form>
                        `;
                        openModal('ALT KATEGORİ Düzenle', content);
                    } catch (error) {
                        alert('Alt kategoriler yüklenirken hata oluştu: ' + error.message);
                    }
                }
            });
        });

        // Alan güncelleme fonksiyonu
        async function updateField(event, fieldName) {
            event.preventDefault();
            const form = event.target;
            const value = form.querySelector('[name="value"]').value;

            try {
                const formData = new FormData();
                formData.append('islem', 'temel_bilgiler');
                formData.append('field', fieldName);
                formData.append('value', value);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    const bilgiDeger = document.querySelector(`.bilgi-deger[data-type="${fieldName}"]`);
                    if (bilgiDeger) {
                        bilgiDeger.querySelector('span').textContent = value;
                    }
                    closeModal(form);
                } else {
                    throw new Error(data.error || 'Güncelleme başarısız');
                }
            } catch (error) {
                alert('Hata: ' + error.message);
            }
            return false;
        }

        // Kategori güncelleme fonksiyonu
        async function updateKategori(event, type) {
            event.preventDefault();
            const form = event.target;
            const value = form.querySelector('[name="value"]').value;

            try {
                const formData = new FormData();
                formData.append('islem', 'kategori_guncelle');
                formData.append('type', type);
                formData.append('value', value);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    // Ana kategori güncellendiğinde
                    if (type === 'ana_kategori') {
                        const altKategoriDiv = document.querySelector('.bilgi-satir[data-type="alt_kategori"]');
                        
                        // Alt kategorileri kontrol et
                        const altKatResponse = await fetch(`get_alt_kategoriler.php?ana_kategori_id=${value}`);
                        const altKategoriler = await altKatResponse.json();
                        
                        if (altKategoriler.length > 0) {
                            // Alt kategori varsa göster
                            altKategoriDiv.style.display = 'block';
                            document.querySelector('.bilgi-deger[data-type="alt_kategori"] span').textContent = 'Seçiniz';
                        } else {
                            // Alt kategori yoksa gizle
                            altKategoriDiv.style.display = 'none';
                        }
                    }
                    
                    // UI güncelleme
                    const bilgiDeger = document.querySelector(`.bilgi-deger[data-type="${type}"]`);
                    if (bilgiDeger) {
                        bilgiDeger.querySelector('span').textContent = data.kategori_adi;
                    }
                    closeModal(form);
                } else {
                    throw new Error(data.error || 'Güncelleme başarısız');
                }
            } catch (error) {
                alert('Hata: ' + error.message);
            }
            return false;
        }

        // Yorum işlemleri için fonksiyonlar
        function yorumDuzenle(yorumId) {
            // Yorum düzenleme işlemi
        }

        function yorumSil(yorumId) {
            if(confirm('Bu yorumu silmek istediğinize emin misiniz?')) {
                // Yorum silme işlemi
            }
        }

        // Soru işlemleri için fonksiyonlar
        function soruCevapla(soruId) {
            const cevap = prompt('Cevabınızı yazın:');
            if(cevap) {
                // Soru cevaplama işlemi
            }
        }

        function soruSil(soruId) {
            if(confirm('Bu soruyu silmek istediğinize emin misiniz?')) {
                // Soru silme işlemi
            }
        }

        // Yorum durumunu değiştirme fonksiyonu
        async function yorumDurumDegistir(yorumId, yeniDurum) {
            try {
                const durumElement = document.querySelector(`.yorum-kutusu[data-id="${yorumId}"] .yorum-durum`);
                
                const response = await fetch('yorum_islem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `islem=durum_degistir&yorum_id=${yorumId}&yeni_durum=${yeniDurum}`
                });

                const data = await response.json();
                if (data.success) {
                    // UI'ı güncelle
                    durumElement.classList.toggle('yayinda');
                    durumElement.classList.toggle('beklemede');
                    durumElement.textContent = yeniDurum ? 'Yayında' : 'Beklemede';
                    
                    // Göz ikonunu güncelle
                    const durumButon = durumElement.closest('.yorum-kutusu')
                        .querySelector('.yorum-islemler .islem-btn:first-child');
                    durumButon.innerHTML = `<i class="fas ${yeniDurum ? 'fa-eye-slash' : 'fa-eye'}"></i>`;
                    durumButon.setAttribute('onclick', `yorumDurumDegistir(${yorumId}, ${yeniDurum ? 0 : 1})`);
                } else {
                    throw new Error(data.error || 'İşlem başarısız');
                }
            } catch (error) {
                console.error('Hata:', error);
                alert('Hata: ' + error.message);
            }
        }

        // Yorum silme fonksiyonu
        async function yorumSil(yorumId) {
            if(!confirm('Bu yorumu silmek istediğinize emin misiniz?')) return;
            
            try {
                const response = await fetch('yorum_islem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `islem=sil&yorum_id=${yorumId}`
                });

                const data = await response.json();
                if (data.success) {
                    const yorumKutusu = document.querySelector(`.yorum-kutusu[data-id="${yorumId}"]`);
                    if (yorumKutusu) {
                        yorumKutusu.remove();
                        // Yorum sayısını güncelle
                        const yorumSayisi = document.querySelector('.yorum-sayisi');
                        const mevcutSayi = parseInt(yorumSayisi.textContent);
                        yorumSayisi.textContent = `${mevcutSayi - 1} Yorum`;
                    }
                } else {
                    throw new Error(data.error || 'Silme işlemi başarısız');
                }
            } catch (error) {
                console.error('Hata:', error);
                alert('Hata: ' + error.message);
            }
        }

        // Soru durumunu değiştirme fonksiyonu
        async function soruDurumDegistir(soruId, yeniDurum) {
            try {
                const durumElement = document.querySelector(`.soru-kutusu[data-id="${soruId}"] .soru-durum`);
                
                const response = await fetch('soru_islem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `islem=durum_degistir&soru_id=${soruId}&yeni_durum=${yeniDurum}`
                });

                const data = await response.json();
                if (data.success) {
                    durumElement.classList.toggle('yayinda');
                    durumElement.classList.toggle('beklemede');
                    durumElement.textContent = yeniDurum ? 'Yayında' : 'Beklemede';
                    
                    const durumButon = durumElement.closest('.soru-kutusu')
                        .querySelector('.soru-islemler .islem-btn:first-child');
                    durumButon.innerHTML = `<i class="fas ${yeniDurum ? 'fa-eye-slash' : 'fa-eye'}"></i>`;
                    durumButon.setAttribute('onclick', `soruDurumDegistir(${soruId}, ${yeniDurum ? 0 : 1})`);
                } else {
                    throw new Error(data.error || 'İşlem başarısız');
                }
            } catch (error) {
                console.error('Hata:', error);
                alert('Hata: ' + error.message);
            }
        }

        // Soru cevaplama fonksiyonu
        async function soruCevapla(soruId) {
            const cevap = prompt('Cevabınızı yazın:');
            if (!cevap) return;

            try {
                const response = await fetch('soru_islem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `islem=cevapla&soru_id=${soruId}&cevap=${encodeURIComponent(cevap)}`
                });

                const data = await response.json();
                if (data.success) {
                    const soruKutusu = document.querySelector(`.soru-kutusu[data-id="${soruId}"]`);
                    const adminCevap = soruKutusu.querySelector('.admin-cevap') || document.createElement('div');
                    adminCevap.className = `admin-cevap ${cevap === 'Cevap bekleniyor' ? 'bekliyor' : ''}`;
                    adminCevap.innerHTML = `<strong>Admin Cevabı:</strong>${cevap}`;
                    
                    if (!soruKutusu.querySelector('.admin-cevap')) {
                        soruKutusu.querySelector('.soru-icerik-metin').after(adminCevap);
                    }
                } else {
                    throw new Error(data.error || 'Cevap kaydedilemedi');
                }
            } catch (error) {
                console.error('Hata:', error);
                alert('Hata: ' + error.message);
            }
        }

        // Soru silme fonksiyonu
        async function soruSil(soruId) {
            if(!confirm('Bu soruyu silmek istediğinize emin misiniz?')) return;
            
            try {
                const response = await fetch('soru_islem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `islem=sil&soru_id=${soruId}`
                });

                const data = await response.json();
                if (data.success) {
                    const soruKutusu = document.querySelector(`.soru-kutusu[data-id="${soruId}"]`);
                    if (soruKutusu) {
                        soruKutusu.remove();
                        const soruSayisi = document.querySelector('.sorular-bolumu .yorum-sayisi');
                        const mevcutSayi = parseInt(soruSayisi.textContent);
                        soruSayisi.textContent = `${mevcutSayi - 1} Soru`;
                    }
                } else {
                    throw new Error(data.error || 'Silme işlemi başarısız');
                }
            } catch (error) {
                console.error('Hata:', error);
                alert('Hata: ' + error.message);
            }
        }

        // Sayfa yüklendiğinde event listener'ları ekle
        document.addEventListener('DOMContentLoaded', function() {
            // Düzenleme butonları için
            document.querySelectorAll('.edit-btn[onclick^="ozellikDuzenle"]').forEach(btn => {
                const ozellikId = btn.getAttribute('onclick').match(/\d+/)[0];
                btn.onclick = () => ozellikDuzenle(ozellikId);
            });

            // Silme butonları için
            document.querySelectorAll('.delete-btn[onclick^="ozellikSil"]').forEach(btn => {
                const ozellikId = btn.getAttribute('onclick').match(/\d+/)[0];
                btn.onclick = () => ozellikSil(ozellikId);
            });
        });
    </script>
</body>
</html> 