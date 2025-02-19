<?php
require_once 'db_connection.php';

// Filtreleme ve sıralama parametrelerini al
$kategori_filtre = $_GET['kategori'] ?? '';
$siralama = $_GET['siralama'] ?? 'yeni';

try {
    $db = Database::getInstance()->getConnection();
    
    // Arama sorgusu varsa
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Ana kategorileri çek
    $anaKategoriQuery = $db->query("
        SELECT ana_kategori_id, ana_kategori_adi 
        FROM ana_kategori 
        ORDER BY ana_kategori_adi ASC
    ");
    $anaKategoriler = $anaKategoriQuery->fetchAll(PDO::FETCH_ASSOC);
    
    // Seçili ana kategori varsa alt kategorileri çek
    $altKategoriler = [];
    if (!empty($_GET['ana_kategori'])) {
        $altKategoriQuery = $db->prepare("
            SELECT alt_kategori_id, alt_kategori_adi 
            FROM alt_kategori 
            WHERE ana_kategori_id = ? 
            ORDER BY alt_kategori_adi ASC
        ");
        $altKategoriQuery->execute([$_GET['ana_kategori']]);
        $altKategoriler = $altKategoriQuery->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Temel SQL sorgusu
    $sql = "SELECT u.*, ak.ana_kategori_adi, altk.alt_kategori_adi, r.resim1 
            FROM urunler u
            LEFT JOIN alt_kategori altk ON u.alt_kategori_id = altk.alt_kategori_id
            LEFT JOIN ana_kategori ak ON altk.ana_kategori_id = ak.ana_kategori_id
            LEFT JOIN resimler r ON u.urun_id = r.urun_id";
    
    $params = [];
    
    // Kategori filtresi
    if (!empty($_GET['alt_kategori'])) {
        $sql .= empty($params) ? " WHERE" : " AND";
        $sql .= " u.alt_kategori_id = ?";
        $params[] = $_GET['alt_kategori'];
    } elseif (!empty($_GET['ana_kategori'])) {
        $sql .= empty($params) ? " WHERE" : " AND";
        $sql .= " altk.ana_kategori_id = ?";
        $params[] = $_GET['ana_kategori'];
    }
    
    // Arama filtresi
    if (!empty($searchQuery)) {
        // Sayısal bir değer girilmişse (ürün ID araması)
        if (is_numeric($searchQuery)) {
            $sql .= " WHERE u.urun_id = ?";
            $params[] = $searchQuery;
        } 
        // Metin girilmişse (ürün adı araması)
        else {
            $sql .= " WHERE u.urun_adi LIKE ?";
            $params[] = '%' . $searchQuery . '%';
        }
    }
    
    // Sıralama
    if (!empty($siralama)) {
        switch ($siralama) {
            case 'eski':
                $sql .= " ORDER BY u.eklenme_tarihi ASC";
                break;
            case 'yeni':
                $sql .= " ORDER BY u.eklenme_tarihi DESC";
                break;
            case 'ad_a_z':
                $sql .= " ORDER BY u.urun_adi ASC";
                break;
            case 'ad_z_a':
                $sql .= " ORDER BY u.urun_adi DESC";
                break;
            default:
                $sql .= " ORDER BY u.eklenme_tarihi DESC"; // Varsayılan sıralama
        }
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Silme işlemi için kontrol
    if(isset($_POST['islem']) && $_POST['islem'] == 'sil' && isset($_POST['urun_id'])) {
        $urun_id = $_POST['urun_id'];
        
        // Transaction başlat
        $db->beginTransaction();
        
        try {
            // Önce resimleri sil
            $stmt = $db->prepare("DELETE FROM resimler WHERE urun_id = ?");
            $stmt->execute([$urun_id]);
            
            // Ürün özelliklerini sil
            $stmt = $db->prepare("DELETE FROM urun_ozellik WHERE urun_id = ?");
            $stmt->execute([$urun_id]);
            
            // Son olarak ürünü sil
            $stmt = $db->prepare("DELETE FROM urunler WHERE urun_id = ?");
            $stmt->execute([$urun_id]);
            
            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Ürün başarıyla silindi']);
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Ürün silinirken hata oluştu: ' . $e->getMessage()]);
            exit;
        }
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
    <title>Beydem Hırdavat - Ürün Ayarları</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>body {
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
            margin-left: 300px;
            width: calc(100% - 300px);
        }

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-container {
            flex: 1;
            display: flex;
            align-items: center;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 300px;
        }

        .search-input {
            flex: 1;
            height: 45px;
            padding: 0 15px;
            border: none;
            font-size: 14px;
            color: #333;
        }

        .search-input:focus {
            outline: none;
        }

        .search-button {
            width: 45px;
            height: 45px;
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-container {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 2;
        }

        .kategori-filtreler {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .filter-select {
            height: 45px;
            padding: 0 15px;
            border: none;
            border-radius: 8px;
            background: white;
            color: #333;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
        }

        .reset-btn {
            height: 45px;
            width: 45px;
            padding: 0;
            background: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .siralama-filter {
            width: 150px;
            flex: none;
        }

        .urun-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px;
        }

        .urun-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .urun-card:hover {
            transform: translateY(-5px);
        }

        .urun-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .urun-detay {
            padding: 20px;
        }

        .urun-adi {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .urun-kategori {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .urun-tarih {
            color: #666;
            font-size: 14px;
        }

        .urun-islemler {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 10px;
            border-top: 1px solid #eee;
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

        /* Tıklanabilir kartlar için stil */
        .urun-card {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .urun-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Silme butonu için stil */
        .sil-btn {
            color: #dc3545;
        }

        .sil-btn:hover {
            color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="header">
                <div class="search-container">
                    <input type="text" class="search-input" id="searchInput" placeholder="Ürün Numarası veya Adı">
                    <button class="search-button" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <select class="filter-select" id="anaKategoriFilter">
                    <option value="">Kategori Seçiniz</option>
                    <?php foreach($anaKategoriler as $kat): ?>
                        <option value="<?php echo $kat['ana_kategori_id']; ?>" 
                            <?php echo isset($_GET['ana_kategori']) && $_GET['ana_kategori'] == $kat['ana_kategori_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kat['ana_kategori_adi']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <select class="filter-select" id="altKategoriFilter" <?php echo empty($altKategoriler) ? 'disabled' : ''; ?>>
                    <option value="">Alt Kategori Seçiniz</option>
                    <?php foreach($altKategoriler as $kat): ?>
                        <option value="<?php echo $kat['alt_kategori_id']; ?>"
                            <?php echo isset($_GET['alt_kategori']) && $_GET['alt_kategori'] == $kat['alt_kategori_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kat['alt_kategori_adi']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button id="resetKategori" class="reset-btn" title="Sıfırla">
                    <i class="fas fa-sync-alt"></i>
                </button>
                
                <select class="filter-select siralama-filter" id="siralamaFilter">
                    <option value="yeni" <?php echo $siralama == 'yeni' ? 'selected' : ''; ?>>En Yeni</option>
                    <option value="eski" <?php echo $siralama == 'eski' ? 'selected' : ''; ?>>En Eski</option>
                    <option value="ad_a_z" <?php echo $siralama == 'ad_a_z' ? 'selected' : ''; ?>>A-Z</option>
                    <option value="ad_z_a" <?php echo $siralama == 'ad_z_a' ? 'selected' : ''; ?>>Z-A</option>
                </select>
            </div>

            <div class="urun-grid">
                <?php foreach($urunler as $urun): ?>
                    <div class="urun-card" data-id="<?php echo $urun['urun_id']; ?>">
                        <img src="<?php echo $urun['resim1'] ? 'data:image/jpeg;base64,'.base64_encode($urun['resim1']) : 'placeholder.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($urun['urun_adi']); ?>" 
                             class="urun-image">
                        <div class="urun-detay">
                            <div class="urun-adi"><?php echo htmlspecialchars($urun['urun_adi']); ?></div>
                            <div class="urun-kategori">
                                <?php 
                                $kategoriText = [];
                                if (!empty($urun['ana_kategori_adi'])) {
                                    $kategoriText[] = htmlspecialchars($urun['ana_kategori_adi']);
                                }
                                if (!empty($urun['alt_kategori_adi'])) {
                                    $kategoriText[] = htmlspecialchars($urun['alt_kategori_adi']);
                                }
                                echo implode(' > ', $kategoriText);
                                ?>
                            </div>
                            <div class="urun-tarih">Eklenme: <?php echo date('d.m.Y', strtotime($urun['eklenme_tarihi'])); ?></div>
                        </div>
                        <div class="urun-islemler">
                            <a href="urun_duzenle.php?id=<?php echo $urun['urun_id']; ?>" class="islem-btn" title="Düzenle">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="islem-btn sil-btn" title="Sil" data-id="<?php echo $urun['urun_id']; ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Arama butonu tıklama olayı
        document.getElementById('searchButton').addEventListener('click', function() {
            performSearch();
        });

        // Enter tuşu ile arama
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });

        // Arama fonksiyonu
        function performSearch() {
            const searchText = document.getElementById('searchInput').value.trim();
            let url = new URL(window.location.href);
            
            if (searchText.length > 0) {
                url.searchParams.set('search', searchText);
            } else {
                url.searchParams.delete('search');
            }
            
            // Mevcut sıralama parametresini koru
            const currentSort = url.searchParams.get('siralama');
            if (currentSort) {
                url.searchParams.set('siralama', currentSort);
            }
            
            window.location.href = url.toString();
        }

        // URL'den search parametresini al ve input'a yerleştir
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search');
            if (searchParam) {
                document.getElementById('searchInput').value = searchParam;
            }
        });

        // Ana kategori değiştiğinde
        document.getElementById('anaKategoriFilter').addEventListener('change', function() {
            const anaKategoriId = this.value;
            let url = new URL(window.location.href);
            
            if (anaKategoriId) {
                url.searchParams.set('ana_kategori', anaKategoriId);
                url.searchParams.delete('alt_kategori');
            } else {
                url.searchParams.delete('ana_kategori');
                url.searchParams.delete('alt_kategori');
            }
            
            window.location.href = url.toString();
        });

        // Alt kategori değiştiğinde
        if (document.getElementById('altKategoriFilter')) {
            document.getElementById('altKategoriFilter').addEventListener('change', function() {
                const altKategoriId = this.value;
                let url = new URL(window.location.href);
                
                if (altKategoriId) {
                    url.searchParams.set('alt_kategori', altKategoriId);
                } else {
                    url.searchParams.delete('alt_kategori');
                }
                
                window.location.href = url.toString();
            });
        }

        // Sıfırlama butonu
        document.getElementById('resetKategori').addEventListener('click', function() {
            let url = new URL(window.location.href);
            url.searchParams.delete('ana_kategori');
            url.searchParams.delete('alt_kategori');
            url.searchParams.delete('siralama'); // Sıralamayı da sıfırla
            url.searchParams.delete('search'); // Aramayı da sıfırla
            window.location.href = url.toString();
        });

        // Silme işlemi için
        document.querySelectorAll('.sil-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation(); // Kartın tıklama olayını engelle
                const urunId = this.dataset.id;
                const urunCard = this.closest('.urun-card');
                
                if(confirm('Bu ürünü silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) {
                    fetch('urun_ayarlari.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `islem=sil&urun_id=${urunId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            urunCard.remove();
                            alert(data.message);
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        alert('Silme işlemi sırasında bir hata oluştu');
                    });
                }
            });
        });

        // Düzenleme için tüm kartı ve düzenleme butonunu tıklanabilir yap
        document.querySelectorAll('.urun-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Silme butonuna tıklanmadıysa düzenleme sayfasına git
                if (!e.target.closest('.sil-btn')) {
                    const urunId = this.dataset.id;
                    window.location.href = `urun_duzenle.php?id=${urunId}`;
                }
            });
        });

        // Düzenleme butonu için ayrı olay dinleyici
        document.querySelectorAll('.islem-btn[title="Düzenle"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation(); // Kartın tıklama olayını engelle
                const urunId = this.closest('.urun-card').dataset.id;
                window.location.href = `urun_duzenle.php?id=${urunId}`;
            });
        });

        // Sıralama değiştiğinde
        document.getElementById('siralamaFilter').addEventListener('change', function() {
            let url = new URL(window.location.href);
            
            // Sıralama değerini URL'ye ekle
            if (this.value) {
                url.searchParams.set('siralama', this.value);
            } else {
                url.searchParams.delete('siralama');
            }
            
            // Sayfayı yenile
            window.location.href = url.toString();
        });
    </script>
</body>
</html>
