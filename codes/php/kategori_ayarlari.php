<?php
require_once 'db_connection.php';

// AJAX isteklerini işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance()->getConnection();
        $islem = $_POST['islem'] ?? '';

        switch ($islem) {
            case 'ekle':
                $kategoriAdi = trim($_POST['kategori_adi']);
                $parentId = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
                
                $query = $db->prepare("INSERT INTO kategori (kategori_adi, parent_id) VALUES (?, ?)");
                $query->execute([$kategoriAdi, $parentId]);
                
                $yeniId = $db->lastInsertId();
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Kategori başarıyla eklendi',
                    'kategori' => [
                        'id' => $yeniId,
                        'adi' => $kategoriAdi,
                        'parent_id' => $parentId
                    ]
                ]);
                exit;

            case 'sil':
                $kategoriId = $_POST['kategori_id'];
                
                // Önce alt kategorileri kontrol et
                $altKategoriQuery = $db->prepare("SELECT COUNT(*) FROM kategori WHERE parent_id = ?");
                $altKategoriQuery->execute([$kategoriId]);
                $altKategoriSayisi = $altKategoriQuery->fetchColumn();
                
                if ($altKategoriSayisi > 0) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Bu kategorinin alt kategorileri var. Önce alt kategorileri silmelisiniz.'
                    ]);
                    exit;
                }
                
                // Kategoriye ait ürünleri kontrol et
                $urunQuery = $db->prepare("SELECT COUNT(*) FROM urunler WHERE kategori_id = ?");
                $urunQuery->execute([$kategoriId]);
                $urunSayisi = $urunQuery->fetchColumn();
                
                if ($urunSayisi > 0) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Bu kategoride ürünler var. Önce ürünleri silmelisiniz.'
                    ]);
                    exit;
                }
                
                $query = $db->prepare("DELETE FROM kategori WHERE kategori_id = ?");
                $query->execute([$kategoriId]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Kategori başarıyla silindi'
                ]);
                exit;

            case 'duzenle':
                $kategoriId = $_POST['kategori_id'];
                $kategoriAdi = trim($_POST['kategori_adi']);
                
                $query = $db->prepare("UPDATE kategori SET kategori_adi = ? WHERE kategori_id = ?");
                $query->execute([$kategoriAdi, $kategoriId]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Kategori başarıyla güncellendi',
                    'kategori' => [
                        'id' => $kategoriId,
                        'adi' => $kategoriAdi
                    ]
                ]);
                exit;
        }
    } catch (PDOException $e) {
        error_log("Kategori işlem hatası: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'İşlem sırasında bir hata oluştu'
        ]);
        exit;
    }
}

// Normal sayfa yüklemesi için kategorileri çek
try {
    $db = Database::getInstance()->getConnection();
    
    // Ana kategorileri çek
    $anaKategoriQuery = $db->prepare("SELECT kategori_id, kategori_adi FROM kategori WHERE parent_id IS NULL ORDER BY kategori_id");
    $anaKategoriQuery->execute();
    $anaKategoriler = $anaKategoriQuery->fetchAll(PDO::FETCH_ASSOC);

    // Alt kategorileri çek
    $altKategoriQuery = $db->prepare("
        SELECT k1.kategori_id, k1.kategori_adi, k1.parent_id, k2.kategori_adi as parent_adi
        FROM kategori k1
        LEFT JOIN kategori k2 ON k1.parent_id = k2.kategori_id
        WHERE k1.parent_id IS NOT NULL
        ORDER BY k1.parent_id, k1.kategori_id
    ");
    $altKategoriQuery->execute();
    $altKategoriler = $altKategoriQuery->fetchAll(PDO::FETCH_ASSOC);

    // Alt kategorileri parent_id'ye göre grupla
    $grupluAltKategoriler = [];
    foreach ($altKategoriler as $kategori) {
        $grupluAltKategoriler[$kategori['parent_id']][] = $kategori;
    }

} catch (Exception $e) {
    error_log("Hata: " . $e->getMessage());
    die("Bir hata oluştu: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beydem Hırdavat - Kategori Ayarları</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
        }

        .sidebar {
            width: 280px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px 0;
            flex-shrink: 0;
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
            color: #666;
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
            color: white;
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
        }

        .main-content {
            flex-grow: 1;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .kategori-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .geri-btn {
            color: #666;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .kategori-baslik {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        .kategori-liste {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .kategori-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .kategori-item:last-child {
            border-bottom: none;
        }

        .kategori-adi {
            color: #333;
            font-size: 16px;
        }

        .kategori-islemler {
            display: flex;
            gap: 10px;
        }

        .islem-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-size: 16px;
            padding: 5px;
        }

        .islem-btn:hover {
            color: #ff6b00;
        }

        .yeni-kategori {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            align-items: center;
        }

        .yeni-kategori input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .ekle-btn {
            background-color: #ff6b00;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .ekle-btn:hover {
            background-color: #e65c00;
        }

        .ana-kategori {
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }

        .ana-kategori-baslik {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            cursor: pointer;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .alt-kategoriler {
            padding-left: 30px;
            display: none;
            background-color: #fff;
        }

        .alt-kategoriler.active {
            display: block;
        }

        .kategori-adi {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            color: #666;
        }

        .toggle-icon.active {
            transform: rotate(90deg);
        }

        .yeni-alt-kategori {
            margin-left: 30px;
            margin-top: 10px;
            display: none;
        }

        .yeni-alt-kategori.active {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2 class="panel-title">PANEL AYARLARI</h2>
            <ul class="menu-list">
                <a href="urun_ayarlari.php" class="menu-item">
                    <i class="fas fa-box"></i>
                    Ürün Ayarları
                </a>
                <a href="kategori_ayarlari.php" class="menu-item active">
                    <i class="fas fa-tags"></i>
                    Kategori Ayarları
                </a>
                <a href="iletisim_kayitlari.php" class="menu-item">
                    <i class="fas fa-address-book"></i>
                    İletişim Kayıtları
                </a>
                <a href="stok_kontrolleri.php" class="menu-item">
                    <i class="fas fa-box-archive"></i>
                    Stok Kontrolleri
                </a>
                <a href="satislar.php" class="menu-item">
                    <i class="fas fa-shopping-cart"></i>
                    Satışlar
                </a>
            </ul>
        </div>

        <div class="main-content">
            <div class="kategori-header">
                <a href="urun_ayarlari.php" class="geri-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="kategori-baslik">KATEGORİ AYARLARI</h2>
                <div></div>
            </div>

            <div class="kategori-liste">
                <?php foreach($anaKategoriler as $anaKategori): ?>
                    <div class="ana-kategori" data-id="<?php echo $anaKategori['kategori_id']; ?>">
                        <div class="ana-kategori-baslik">
                            <div class="kategori-adi">
                                <i class="fas fa-chevron-right toggle-icon"></i>
                                <span class="kategori-adi-text"><?php echo htmlspecialchars($anaKategori['kategori_adi']); ?></span>
                            </div>
                            <div class="kategori-islemler">
                                <button class="islem-btn yeni-alt-kategori-btn" title="Alt Kategori Ekle">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="islem-btn" title="Düzenle">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <button class="islem-btn" title="Sil">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="alt-kategoriler">
                            <?php if(isset($grupluAltKategoriler[$anaKategori['kategori_id']])): ?>
                                <?php foreach($grupluAltKategoriler[$anaKategori['kategori_id']] as $altKategori): ?>
                                    <div class="kategori-item" data-id="<?php echo $altKategori['kategori_id']; ?>">
                                        <span class="kategori-adi">
                                            <?php echo htmlspecialchars($altKategori['kategori_adi']); ?>
                                        </span>
                                        <div class="kategori-islemler">
                                            <button class="islem-btn" title="Düzenle">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <button class="islem-btn" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div class="yeni-alt-kategori">
                                <input type="text" placeholder="Yeni Alt Kategori Adı">
                                <button class="ekle-btn">Ekle</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="yeni-kategori">
                    <input type="text" placeholder="Yeni Ana Kategori Adı Giriniz">
                    <button class="ekle-btn">Ekle</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ana kategorileri açıp kapama
        document.querySelectorAll('.ana-kategori-baslik').forEach(baslik => {
            baslik.addEventListener('click', function(e) {
                if (e.target.closest('.kategori-islemler')) return;
                
                const anaKategori = this.closest('.ana-kategori');
                const altKategoriler = anaKategori.querySelector('.alt-kategoriler');
                const toggleIcon = this.querySelector('.toggle-icon');
                
                altKategoriler.classList.toggle('active');
                toggleIcon.classList.toggle('active');
            });
        });

        // Alt kategori ekleme butonları
        document.querySelectorAll('.yeni-alt-kategori-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const anaKategori = this.closest('.ana-kategori');
                const yeniAltKategori = anaKategori.querySelector('.yeni-alt-kategori');
                const altKategoriler = anaKategori.querySelector('.alt-kategoriler');
                
                yeniAltKategori.classList.toggle('active');
                altKategoriler.classList.add('active');
            });
        });

        // Kategori ekleme işlemleri
        document.querySelectorAll('.ekle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const kategoriAdi = input.value.trim();
                const anaKategori = this.closest('.ana-kategori');
                const parentId = anaKategori ? anaKategori.dataset.id : null;
                
                if(kategoriAdi) {
                    const formData = new FormData();
                    formData.append('islem', 'ekle');
                    formData.append('kategori_adi', kategoriAdi);
                    if(parentId) formData.append('parent_id', parentId);
                    
                    fetch('kategori_ayarlari.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if(data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        alert('Bir hata oluştu');
                    });
                }
            });
        });

        // Silme işlemi
        document.querySelectorAll('.islem-btn[title="Sil"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const kategoriItem = this.closest('.kategori-item') || this.closest('.ana-kategori');
                const kategoriId = kategoriItem.dataset.id;
                const kategoriAdi = kategoriItem.querySelector('.kategori-adi').textContent.trim();

                if(confirm(`"${kategoriAdi}" kategorisini silmek istediğinize emin misiniz?`)) {
                    const formData = new FormData();
                    formData.append('islem', 'sil');
                    formData.append('kategori_id', kategoriId);

                    fetch('kategori_ayarlari.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if(data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        alert('Bir hata oluştu');
                    });
                }
            });
        });

        // Düzenleme işlemi
        document.querySelectorAll('.islem-btn[title="Düzenle"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const kategoriItem = this.closest('.kategori-item') || this.closest('.ana-kategori');
                const kategoriId = kategoriItem.dataset.id;
                const kategoriAdiElement = kategoriItem.querySelector('.kategori-adi');
                const mevcutAd = kategoriAdiElement.textContent.trim();
                
                const yeniAd = prompt('Yeni kategori adını giriniz:', mevcutAd);
                
                if(yeniAd && yeniAd !== mevcutAd) {
                    const formData = new FormData();
                    formData.append('islem', 'duzenle');
                    formData.append('kategori_id', kategoriId);
                    formData.append('kategori_adi', yeniAd);

                    fetch('kategori_ayarlari.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if(data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        alert('Bir hata oluştu');
                    });
                }
            });
        });
    </script>
</body>
</html> 