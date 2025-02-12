<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db = Database::getInstance()->getConnection();

        // **HATA 1 ÇÖZÜMÜ: alt_kategori POST isteğinde gelmiyorsa varsayılan olarak NULL yap**
        $urun_adi = $_POST['urun_adi'];
        $marka_adi = $_POST['marka_adi'];
        $ana_kategori = $_POST['ana_kategori'];
        $alt_kategori = isset($_POST['alt_kategori']) ? $_POST['alt_kategori'] : null;
        $eklenme_tarihi = date("Y-m-d H:i:s");

        // Kategori ID belirleme (alt kategori varsa onu kullan, yoksa ana kategoriyi al)
        $kategori_id = !empty($alt_kategori) ? $alt_kategori : $ana_kategori;

        // **1️⃣ Ürün Bilgilerini Kaydet (urun_id'yi AUTO_INCREMENT olarak alıyoruz)**
        $stmt_urun = $db->prepare("INSERT INTO urunler (urun_adi, marka_adi, kategori_id, eklenme_tarihi) 
                                   VALUES (?, ?, ?, ?)");
        $stmt_urun->execute([$urun_adi, $marka_adi, $kategori_id, $eklenme_tarihi]);

        // **SON EKLENEN URUN ID'SINI AL**
        $urun_id = $db->lastInsertId(); // 🔥 ÇÖZÜM: AUTO_INCREMENT ID al

        // **HATA ÇÖZÜMÜ: resimler tablosunun var olup olmadığını kontrol et**
        $checkTable = $db->query("SHOW TABLES LIKE 'resimler'");
        if ($checkTable->rowCount() == 0) {
            throw new Exception("Hata: 'resimler' tablosu bulunamadı! Lütfen MySQL'de tabloyu oluştur.");
        }

        // **2️⃣ Ürün Resimlerini Kaydet (`resimler` tablosuna ekleniyor)**
        if (isset($_FILES['resimler']) && count($_FILES['resimler']['name']) == 4) {
            $stmt_resim = $db->prepare("INSERT INTO resimler (urun_id, resim1, resim2, resim3, resim4) VALUES (?, ?, ?, ?, ?)");

            $resimler = [];
            for ($i = 0; $i < 4; $i++) {
                $tmp_name = $_FILES['resimler']['tmp_name'][$i];
                if (is_uploaded_file($tmp_name)) {
                    $resimler[$i] = file_get_contents($tmp_name);
                } else {
                    throw new Exception("Hata: 4 resim yüklenmelidir!");
                }
            }

            $stmt_resim->execute([$urun_id, $resimler[0], $resimler[1], $resimler[2], $resimler[3]]);
        } else {
            throw new Exception("Hata: 4 resim yüklenmelidir!");
        }

        // **3️⃣ Ürün Özelliklerini Kaydet**
        if (!empty($_POST['ozellik_adi']) && !empty($_POST['ozellik_deger'])) {
            $stmt_ozellik = $db->prepare("INSERT INTO urun_ozellik (urun_id, ozellik_adi, ozellik_deger) VALUES (?, ?, ?)");
        
            foreach ($_POST['ozellik_adi'] as $index => $ozellik_adi) {
                $ozellik_deger = $_POST['ozellik_deger'][$index];
        
                // **Boşlukları temizle ve verinin gerçekten dolu olup olmadığını kontrol et**
                if (empty(trim($ozellik_adi)) || empty(trim($ozellik_deger))) {
                    continue; // Eğer veri boş veya sadece boşluklardan oluşuyorsa, kaydetme!
                }
        
                // Eğer özellik adı ve değeri gerçekten doluysa, veri tabanına ekle
                $stmt_ozellik->execute([$urun_id, $ozellik_adi, $ozellik_deger]);
            }
        }
        

        header("Location: urun_ekle.php?success=1");
        exit();
    } catch (Exception $e) {
        echo "<div style='color: red; font-weight: bold;'>Hata: " . $e->getMessage() . "</div>";
    }
}


// Ana kategorileri çek
try {
    $db = Database::getInstance()->getConnection();
    $anaKategoriQuery = $db->query("
        SELECT kategori_id, kategori_adi 
        FROM kategori 
        WHERE parent_id IS NULL 
        ORDER BY kategori_adi
    ");
    $anaKategoriler = $anaKategoriQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Kategoriler yüklenirken hata oluştu: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beydem Hırdavat - Ürün Ekle</title>
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
            display: flex;
            justify-content: center;
        }

        .content-wrapper {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .sayfa-baslik {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .form-columns {
            display: flex;
            gap: 40px;
        }

        .form-column {
            flex: 1;
        }

        .input-row {
            position: relative;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            overflow: hidden;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .input-label {
            padding: 8px 15px;
            color: #333;
            font-size: 14px;
        }

        .input-field {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .input-field input {
            flex: 1;
            padding: 8px 15px;
            border: 1px solid transparent;
            border-radius: 4px;
            background: transparent;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
        }
        .input-field input:focus{
            border: 2px solid #ff6b00;
        }

        .input-field input::placeholder {
            color: #999;
        }

        .settings-btn {
            padding: 12px 15px;
            background: none;
            border: none;
            color: #003366;
            cursor: pointer;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .image-box {
            aspect-ratio: 1;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-box:hover {
            border-color: #ff6b00;
        }

        .image-box i {
            font-size: 20px;
            color: #003366;
        }

        .image-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-box:hover .image-actions {
            opacity: 1;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 4px;
            color: #003366;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-box.has-image i {
            display: none;
        }

        .image-box.has-image {
            border-style: solid;
            background-size: cover;
            background-position: center;
        }

        .property-form {
            display: flex;
            gap: 10px;
        }

        .property-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background: #f8f9fa;
            font-size: 14px;
        }

        .add-btn {
            padding: 12px 25px;
            background: #ff6b00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .add-btn:hover {
            background: #e65100;
        }

        .property-list {
            margin-top: 20px;
            border-radius: 5px;
        }

        .property-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 8px;
        }

        .property-info {
            display: flex;
            gap: 15px;
        }

        .property-name {
            color: #003366;
            font-weight: bold;
        }

        .property-value {
            color: #003366;
            font-weight: normal;
        }

        .property-delete {
            background: none;
            border: none;
            color: #003366;
            cursor: pointer;
            padding: 5px;
        }

        .property-delete:hover {
            opacity: 0.8;
        }

        .form-actions {
            margin-top: 30px;
            text-align: right;
        }

        .save-btn {
            padding: 12px 30px;
            background: #003366;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .save-btn:hover {
            background: #002244;
        }

        .kategori-field {
            display: flex;
            gap: 10px;
        }

        .kategori-select {
            flex: 1;
            padding: 8px 15px;
            border: 2px solid transparent;
            border-radius: 4px;
            background: #f8f9fa;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
            color: #333;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .kategori-select:focus {
            border: 2px solid #ff6b00;
            background: #fff;
        }

        .kategori-select:disabled {
            background: #f0f0f0;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .kategori-select option {
            background: white;
            color: #333;
            padding: 8px;
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
            <div class="content-wrapper">
                <div class="header">
                    <h2 class="sayfa-baslik">Yeni Ürün Ekle</h2>
                </div>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form action="urun_ekle.php" method="POST" enctype="multipart/form-data">
                    <div class="form-columns">
                        <!-- Sol Sütun - Genel Özellikler -->
                        <div class="form-column">
                            <div class="input-row">
                                <div class="input-label">Ürün Numarası</div>
                                <div class="input-field">
                                    <input type="text" name="urun_numarasi" placeholder="Ürün Numarası Giriniz" required>
                                </div>
                            </div>

                            <div class="input-row">
                                <div class="input-label">Ürün Adı</div>
                                <div class="input-field">
                                    <input type="text" name="urun_adi" placeholder="Ürün Adı Giriniz" required>
                                </div>
                            </div>

                            <div class="input-row">
                                <div class="input-label">Marka Adı</div>
                                <div class="input-field">
                                    <input type="text" name="marka_adi" placeholder="Marka Adı Giriniz" required>
                                </div>
                            </div>

                            <div class="input-row">
                                <div class="input-label">Kategori</div>
                                <div class="input-field kategori-field">
                                    <select name="ana_kategori" id="anaKategori" class="kategori-select" required>
                                        <option value="">Ana Kategori Seçiniz</option>
                                        <?php foreach ($anaKategoriler as $kategori): ?>
                                            <option value="<?= htmlspecialchars($kategori['kategori_id']) ?>">
                                                <?= htmlspecialchars($kategori['kategori_adi']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="alt_kategori" id="altKategori" class="kategori-select" disabled style="display: none;">
                                        <option value="">Alt Kategori Seçiniz</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sağ Sütun - Görsel ve Özellik Ekleme -->
                        <div class="form-column">
                            <div class="image-grid">
                                <?php for($i = 0; $i < 4; $i++): ?>
                                <div class="image-box">
                                    <input type="file" name="resimler[]" accept="image/*" class="file-input" hidden required>
                                    <i class="fas fa-image"></i>
                                    <div class="image-actions">
                                        <button type="button" class="action-btn settings" title="Düzenle">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <button type="button" class="action-btn delete" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>

                            <div class="property-form">
                                <input type="text" name="ozellik_adi[]" placeholder="Özellik Adı Giriniz" class="property-input">
                                <input type="text" name="ozellik_deger[]" placeholder="Özellik Değeri Giriniz" class="property-input">
                                <button type="button" class="add-btn" onclick="yeniOzellikEkle()">Ekle</button>
                            </div>
                            <div id="ozellikler-listesi">
                                <!-- Eklenen özellikler buraya gelecek -->
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="save-btn">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.image-box').forEach(box => {
            box.addEventListener('click', function(e) {
                this.querySelector('.file-input').click();
            });
        });

        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    const box = this.closest('.image-box');
                    
                    reader.onload = function(e) {
                        box.style.backgroundImage = `url(${e.target.result})`;
                        box.classList.add('has-image');
                        
                        // Butonları görünür yap
                        box.querySelector('.image-actions').style.display = 'flex';
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        // Özellik ekleme fonksiyonu
        function yeniOzellikEkle() {
            const ozellikAdi = document.querySelector('input[name="ozellik_adi[]"]').value.trim();
            const ozellikDegeri = document.querySelector('input[name="ozellik_deger[]"]').value.trim();
            
            if (ozellikAdi && ozellikDegeri) {
                const liste = document.getElementById('ozellikler-listesi');
                const yeniOzellik = document.createElement('div');
                yeniOzellik.className = 'property-item';
                yeniOzellik.innerHTML = `
                    <div class="property-info">
                        <input type="hidden" name="ozellik_adi[]" value="${ozellikAdi}">
                        <input type="hidden" name="ozellik_deger[]" value="${ozellikDegeri}">
                        <span class="property-name">${ozellikAdi}:</span>
                        <span class="property-value">${ozellikDegeri}</span>
                    </div>
                    <button type="button" class="property-delete" onclick="this.parentElement.remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                liste.appendChild(yeniOzellik);
                
                // Input alanlarını temizle
                document.querySelector('input[name="ozellik_adi[]"]').value = '';
                document.querySelector('input[name="ozellik_deger[]"]').value = '';
            }
        }

        // Özellik silme fonksiyonu
        function ozellikSil(button) {
            button.closest('.property-item').remove();
        }

        // Kategori seçimi için JavaScript
        document.getElementById('anaKategori').addEventListener('change', async function() {
            const altKategoriSelect = document.getElementById('altKategori');
            const secilenAnaKategoriId = this.value;
            
            // Alt kategori select'ini temizle
            altKategoriSelect.innerHTML = '<option value="">Alt Kategori Seçiniz</option>';
            
            if (secilenAnaKategoriId) {
                try {
                    // Alt kategorileri AJAX ile getir
                    const response = await fetch(`get_alt_kategoriler.php?parent_id=${secilenAnaKategoriId}`);
                    const altKategoriler = await response.json();
                    
                    // Alt kategorileri select'e ekle
                    if (altKategoriler.length > 0) {
                        altKategoriler.forEach(kategori => {
                            const option = document.createElement('option');
                            option.value = kategori.kategori_id;
                            option.textContent = kategori.kategori_adi;
                            altKategoriSelect.appendChild(option);
                        });
                        // Alt kategori select'ini aktif et ve göster
                        altKategoriSelect.disabled = false;
                        altKategoriSelect.style.display = 'block';
                    } else {
                        // Alt kategori yoksa, devre dışı bırak ve gizle
                        altKategoriSelect.disabled = true;
                        altKategoriSelect.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Alt kategoriler yüklenirken hata:', error);
                    altKategoriSelect.disabled = true;
                    altKategoriSelect.style.display = 'none';
                }
            } else {
                // Ana kategori seçili değilse alt kategori select'ini deaktif et ve gizle
                altKategoriSelect.disabled = true;
                altKategoriSelect.style.display = 'none';
            }
        });

    </script>
</body>
</html> 