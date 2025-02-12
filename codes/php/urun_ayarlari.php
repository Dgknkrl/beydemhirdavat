<?php
require_once 'db_connection.php';

// Filtreleme ve sıralama parametrelerini al
$kategori_filtre = $_GET['kategori'] ?? '';
$siralama = $_GET['siralama'] ?? 'yeni';

try {
    $db = Database::getInstance()->getConnection();
    
    // Arama sorgusu varsa
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Kategorileri çek (filtreleme için)
    $kategoriQuery = $db->query("SELECT kategori_id, kategori_adi FROM kategori ORDER BY kategori_adi");
    $kategori = $kategoriQuery->fetchAll(PDO::FETCH_ASSOC);
    
    // Temel SQL sorgusu
    $sql = "SELECT u.*, k.kategori_adi, r.resim1 
            FROM urunler u
            LEFT JOIN kategori k ON u.kategori_id = k.kategori_id
            LEFT JOIN resimler r ON u.urun_id = r.urun_id";
    
    $params = [];
    
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
    
    // Kategori filtresi
    if (!empty($kategori_filtre)) {
        $sql .= empty($params) ? " WHERE" : " AND";
        $sql .= " u.kategori_id = ?";
        $params[] = $kategori_filtre;
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
        }
    } else {
        // Varsayılan sıralama
        $sql .= " ORDER BY u.eklenme_tarihi DESC";
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 20px;
        }

        .search-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-button {
            padding: 10px 15px;
            background-color:#ff6b00;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-button:hover {
            background-color: #003d82;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-result-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .search-result-item:hover {
            background-color: #f5f5f5;
        }

        .search-result-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            margin-right: 10px;
        }

        .search-result-info {
            flex: 1;
        }

        .search-result-id {
            font-size: 12px;
            color: #666;
        }

        .search-result-name {
            font-weight: bold;
        }

        .search-result-category {
            font-size: 12px;
            color: #888;
        }

        .filter-container {
            display: flex;
            gap: 10px;
        }

        .filter-select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
            color: #333;
            cursor: pointer;
            min-width: 150px;
        }

        .filter-select:hover {
            border-color: #ff6b00;
        }

        .filter-select:focus {
            outline: none;
            border-color: #ff6b00;
            box-shadow: 0 0 0 2px rgba(255,107,0,0.1);
        }

        .urun-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .urun-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .urun-card:hover {
            transform: translateY(-5px);
        }

        .urun-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .urun-detay {
            padding: 15px;
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
    </style>
</head>
<body>
    <div class="container">
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

        <div class="main-content">
            <div class="header">
                <div class="search-container">
                    <input type="text" class="search-input" id="searchInput" placeholder="Ürün ID veya adı ile ara...">
                    <button class="search-button" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="filter-container">
                    <select class="filter-select kategori-filter">
                        <option value="">Tüm Kategoriler</option>
                        <?php foreach($kategori as $kategori): ?>
                            <option value="<?php echo $kategori['kategori_id']; ?>" 
                                <?php echo $kategori_filtre == $kategori['kategori_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kategori['kategori_adi']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select class="filter-select siralama-filter">
                        <option value="yeni" <?php echo $siralama == 'yeni' ? 'selected' : ''; ?>>En Yeni</option>
                        <option value="eski" <?php echo $siralama == 'eski' ? 'selected' : ''; ?>>En Eski</option>
                        <option value="ad_a_z" <?php echo $siralama == 'ad_a_z' ? 'selected' : ''; ?>>A-Z</option>
                        <option value="ad_z_a" <?php echo $siralama == 'ad_z_a' ? 'selected' : ''; ?>>Z-A</option>
                    </select>
                </div>
            </div>

            <div class="urun-grid">
                <?php foreach($urunler as $urun): ?>
                    <div class="urun-card" data-id="<?php echo $urun['urun_id']; ?>">
                        <img src="<?php echo $urun['resim1'] ? 'data:image/jpeg;base64,'.base64_encode($urun['resim1']) : 'placeholder.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($urun['urun_adi']); ?>" 
                             class="urun-image">
                        <div class="urun-detay">
                            <div class="urun-adi"><?php echo htmlspecialchars($urun['urun_adi']); ?></div>
                            <div class="urun-kategori"><?php echo htmlspecialchars($urun['kategori_adi']); ?></div>
                            <div class="urun-tarih">Eklenme: <?php echo date('d.m.Y', strtotime($urun['eklenme_tarihi'])); ?></div>
                        </div>
                        <div class="urun-islemler">
                            <button class="islem-btn" title="Düzenle">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="islem-btn" title="Sil">
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
            if (searchText.length > 0) {
                let url = new URL(window.location.href);
                url.searchParams.set('search', searchText);
                window.location.href = url.toString();
            } else {
                let url = new URL(window.location.href);
                url.searchParams.delete('search');
                window.location.href = url.toString();
            }
        }

        // URL'den search parametresini al ve input'a yerleştir
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search');
            if (searchParam) {
                document.getElementById('searchInput').value = searchParam;
            }
        });
    </script>
</body>
</html>
