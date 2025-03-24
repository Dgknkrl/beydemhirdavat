<?php
require_once '../includes/db_connection.php';
session_start();

// AJAX isteği kontrolü
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] == 'add_banner' && isset($_FILES['banner_image'])) {
        try {
            $image = file_get_contents($_FILES['banner_image']['tmp_name']);
            $link = isset($_POST['link']) ? $_POST['link'] : '';
            
            $stmt = $conn->prepare("INSERT INTO dinamik_banner (banner_gorsel, link) VALUES (?, ?)");
            $stmt->bind_param("ss", $image, $link);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Banner başarıyla eklendi'
                ]);
            } else {
                throw new Exception("Veritabanı hatası");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Banner eklenirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        exit;
    } 
    
    if ($_POST['action'] == 'delete_banner' && isset($_POST['banner_id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM dinamik_banner WHERE banner_id = ?");
            $stmt->bind_param("i", $_POST['banner_id']);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Banner başarıyla silindi'
                ]);
            } else {
                throw new Exception("Veritabanı hatası");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Banner silinirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    if ($_POST['action'] == 'get_banners') {
        try {
            $result = $conn->query("SELECT banner_id, banner_gorsel, link FROM dinamik_banner");
            $banners = [];
            
            while ($banner = $result->fetch_assoc()) {
                $banners[] = [
                    'id' => $banner['banner_id'],
                    'image' => base64_encode($banner['banner_gorsel']),
                    'link' => $banner['link']
                ];
            }
            
            echo json_encode([
                'success' => true,
                'banners' => $banners
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Bannerlar yüklenirken bir hata oluştu'
            ]);
        }
        exit;
    }

    // Popüler ürün ekleme
    if ($_POST['action'] == 'add_popular_product' && isset($_POST['urun_id'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO populer_urunler (urun_id) VALUES (?)");
            $stmt->bind_param("i", $_POST['urun_id']);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Ürün popüler ürünlere eklendi'
                ]);
            } else {
                throw new Exception("Veritabanı hatası");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Ürün eklenirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    // Popüler ürün silme
    if ($_POST['action'] == 'delete_popular_product' && isset($_POST['populer_id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM populer_urunler WHERE populer_id = ?");
            $stmt->bind_param("i", $_POST['populer_id']);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Ürün popüler listesinden kaldırıldı'
                ]);
            } else {
                throw new Exception("Veritabanı hatası");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Ürün silinirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    // Marka ekleme
    if ($_POST['action'] == 'add_brand' && isset($_FILES['marka_gorsel']) && isset($_POST['marka_adi'])) {
        try {
            $image = file_get_contents($_FILES['marka_gorsel']['tmp_name']);
            $marka_adi = $_POST['marka_adi'];
            
            $stmt = $conn->prepare("INSERT INTO markalar (marka_adi, marka_gorsel) VALUES (?, ?)");
            $stmt->bind_param("ss", $marka_adi, $image);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Marka başarıyla eklendi'
                ]);
            } else {
                throw new Exception("Veritabanı hatası");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Marka eklenirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    // Marka silme
    if ($_POST['action'] == 'delete_brand' && isset($_POST['marka_id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM markalar WHERE marka_id = ?");
            $stmt->bind_param("i", $_POST['marka_id']);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Marka başarıyla silindi'
                ]);
            } else {
                throw new Exception("Veritabanı hatası");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Marka silinirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    // Tüm ürünleri getirme
    if ($_POST['action'] == 'get_all_products') {
        try {
            $query = "SELECT u.urun_id, u.urun_adi, r.resim1 
                     FROM urunler u 
                     LEFT JOIN resimler r ON u.urun_id = r.urun_id";
            $result = $conn->query($query);
            $products = [];
            
            while ($row = $result->fetch_assoc()) {
                $products[] = [
                    'id' => $row['urun_id'],
                    'name' => $row['urun_adi'],
                    'image' => $row['resim1'] ? base64_encode($row['resim1']) : null
                ];
            }
            
            echo json_encode([
                'success' => true,
                'products' => $products
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Ürünler yüklenirken bir hata oluştu'
            ]);
        }
        exit;
    }

    // Popüler ürünleri getirme
    if ($_POST['action'] == 'get_popular_products') {
        try {
            $query = "SELECT p.populer_id, u.urun_id, u.urun_adi, r.resim1 
                     FROM populer_urunler p 
                     JOIN urunler u ON p.urun_id = u.urun_id 
                     LEFT JOIN resimler r ON u.urun_id = r.urun_id";
            $result = $conn->query($query);
            $products = [];
            
            while ($row = $result->fetch_assoc()) {
                $products[] = [
                    'populer_id' => $row['populer_id'],
                    'urun_id' => $row['urun_id'],
                    'name' => $row['urun_adi'],
                    'image' => $row['resim1'] ? base64_encode($row['resim1']) : null
                ];
            }
            
            echo json_encode([
                'success' => true,
                'products' => $products
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Popüler ürünler yüklenirken bir hata oluştu'
            ]);
        }
        exit;
    }

    // Markaları getirme
    if ($_POST['action'] == 'get_brands') {
        try {
            $result = $conn->query("SELECT marka_id, marka_adi, marka_gorsel FROM markalar");
            $brands = [];
            
            while ($brand = $result->fetch_assoc()) {
                $brands[] = [
                    'id' => $brand['marka_id'],
                    'name' => $brand['marka_adi'],
                    'image' => base64_encode($brand['marka_gorsel'])
                ];
            }
            
            echo json_encode([
                'success' => true,
                'brands' => $brands
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Markalar yüklenirken bir hata oluştu'
            ]);
        }
        exit;
    }
}

// Mevcut bannerları çek
$banners = [];
$result = $conn->query("SELECT banner_id, banner_gorsel, link FROM dinamik_banner");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $banners[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa Ayarları - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        *{
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            padding: 20px;
            font-family: 'Arial', sans-serif;
        }
        .container {
            display: flex;
            min-height: calc(100vh - 40px);
        }

        .content {
            flex-grow: 1;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-left: 300px;
            width: calc(100% - 300px);
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .settings-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid #e0e0e0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .settings-card:hover {
            transform: translateY(-5px);
            border-color: #FF6B00;
            box-shadow: 0 6px 12px rgba(255,107,0,0.2);
        }

        .settings-card i {
            font-size: 40px;
            color: #FF6B00;
            margin-bottom: 15px;
        }

        .settings-card h3 {
            margin: 0;
            color: #333;
            font-size: 20px;
            font-weight: 600;
        }

        .settings-card p {
            margin: 10px 0 0;
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .content {
                margin: 20px;
            }
            
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }

        .banner-settings {
            padding: 20px;
        }

        .settings-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .back-button {
            background: #FF6B00;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            margin-right: 20px;
        }

        .back-button:hover {
            background: #e65100;
        }

        .banner-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .banner-form h3 {
            color: #333;
            font-size: 24px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .banner-form h3 i {
            color: #FF6B00;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .submit-button {
            background: #FF6B00;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin: 0 auto;
        }

        .submit-button:hover {
            background: #e65100;
            transform: translateY(-2px);
        }

        .banner-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .banner-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .banner-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff4444;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .banner-item:hover .delete-button {
            opacity: 1;
        }

        .delete-button:hover {
            background: #cc0000;
        }

        /* Alert Stili */
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }

        .alert.success {
            background: #4CAF50;
        }

        .alert.error {
            background: #f44336;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Banner Form Yeni Stili */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }

        .file-upload-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-box {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .file-upload-box i {
            font-size: 48px;
            color: #FF6B00;
            margin-bottom: 15px;
        }

        .file-upload-box p {
            color: #666;
            margin: 10px 0;
        }

        .file-upload-box .selected-file {
            color: #FF6B00;
            font-weight: 500;
            display: none;
        }

        .file-upload-wrapper:hover .file-upload-box {
            border-color: #FF6B00;
            background: #fff8f3;
        }

        .link-input-wrapper {
            position: relative;
            margin-top: 20px;
        }

        .link-input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .link-input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .link-input:focus {
            border-color: #FF6B00;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,107,0,0.1);
        }

        .link-input:hover {
            border-color: #FF6B00;
        }

        .link-input::placeholder {
            color: #999;
        }

        /* Mevcut banner listesinde link gösterimi için stil */
        .banner-item .banner-link {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .banner-item:hover .banner-link {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="content">
            <div class="settings-grid" id="main-settings">
                <!-- Banner Ayarları -->
                <div class="settings-card" onclick="showSettings('banner')">
                    <i class="fas fa-images"></i>
                    <h3>BANNER AYARLARI</h3>
                    <p>Ana sayfa banner görsellerini ve ayarlarını yönetin</p>
                </div>

                <!-- Popüler Ürün Ayarları -->
                <div class="settings-card" onclick="showSettings('populer')">
                    <i class="fas fa-star"></i>
                    <h3>POPÜLER ÜRÜN AYARLARI</h3>
                    <p>Ana sayfada gösterilecek popüler ürünleri seçin</p>
                </div>

                <!-- Marka Ayarları -->
                <div class="settings-card" onclick="showSettings('marka')">
                    <i class="fas fa-trademark"></i>
                    <h3>MARKA AYARLARI</h3>
                    <p>Ana sayfada gösterilecek markaları yönetin</p>
                </div>
            </div>

            <!-- Banner Ayarları İçeriği -->
            <div class="banner-settings" id="banner-settings" style="display: none;">
                <div class="settings-header">
                    <button class="back-button" onclick="showMainSettings()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h2>Banner Ayarları</h2>
                </div>

                <!-- Banner Ekleme Formu -->
                <div class="banner-form">
                    <h3><i class="fas fa-plus-circle"></i> Yeni Banner Ekle</h3>
                    <form id="bannerForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="file-upload-wrapper">
                                <input type="file" name="banner_image" id="banner_image" accept="image/*" required class="file-upload-input">
                                <div class="file-upload-box">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Banner görselini seçmek için tıklayın veya sürükleyin</p>
                                    <span class="selected-file"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="link-input-wrapper">
                                <i class="fas fa-link"></i>
                                <input type="url" name="link" id="link" placeholder="Banner için yönlendirilecek link (opsiyonel)" class="link-input">
                            </div>
                        </div>
                        <button type="submit" class="submit-button">
                            <i class="fas fa-plus"></i> Banner Ekle
                        </button>
                    </form>
                </div>

                <!-- Bildirim için alert div'i -->
                <div id="alert" class="alert" style="display: none;"></div>

                <!-- Mevcut Bannerlar -->
                <div class="banner-list">
                    <h3>Mevcut Bannerlar</h3>
                    <div class="banner-grid">
                        <?php foreach ($banners as $banner): ?>
                        <div class="banner-item">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($banner['banner_gorsel']); ?>" alt="Banner">
                            <?php if ($banner['link']): ?>
                            <div class="banner-link"><?php echo $banner['link']; ?></div>
                            <?php endif; ?>
                            <form method="POST" class="delete-form">
                                <input type="hidden" name="action" value="delete_banner">
                                <input type="hidden" name="banner_id" value="<?php echo $banner['banner_id']; ?>">
                                <button type="submit" class="delete-button">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Popüler Ürünler Ayarları İçeriği -->
            <div class="populer-settings" id="populer-settings" style="display: none;">
                <div class="settings-header">
                    <button class="back-button" onclick="showMainSettings()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h2>Popüler Ürün Ayarları</h2>
                </div>

                <div class="banner-form">
                    <h3><i class="fas fa-star"></i> Popüler Ürün Ekle</h3>
                    <form id="populerForm">
                        <div class="form-group">
                            <select name="urun_id" id="urun_select" class="link-input" required>
                                <option value="">Ürün Seçin</option>
                            </select>
                        </div>
                        <button type="submit" class="submit-button">
                            <i class="fas fa-plus"></i> Ürünü Ekle
                        </button>
                    </form>
                </div>

                <div class="populer-list">
                    <h3>Mevcut Popüler Ürünler</h3>
                    <div class="banner-grid" id="populer-grid"></div>
                </div>
            </div>

            <!-- Marka Ayarları İçeriği -->
            <div class="marka-settings" id="marka-settings" style="display: none;">
                <div class="settings-header">
                    <button class="back-button" onclick="showMainSettings()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h2>Marka Ayarları</h2>
                </div>

                <div class="banner-form">
                    <h3><i class="fas fa-trademark"></i> Yeni Marka Ekle</h3>
                    <form id="markaForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="file-upload-wrapper">
                                <input type="file" name="marka_gorsel" id="marka_gorsel" accept="image/*" required class="file-upload-input">
                                <div class="file-upload-box">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Marka görselini seçmek için tıklayın veya sürükleyin</p>
                                    <span class="selected-file"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="link-input-wrapper">
                                <i class="fas fa-signature"></i>
                                <input type="text" name="marka_adi" id="marka_adi" placeholder="Marka adı" class="link-input" required>
                            </div>
                        </div>
                        <button type="submit" class="submit-button">
                            <i class="fas fa-plus"></i> Marka Ekle
                        </button>
                    </form>
                </div>

                <div class="marka-list">
                    <h3>Mevcut Markalar</h3>
                    <div class="banner-grid" id="marka-grid"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSettings(type) {
            document.getElementById('main-settings').style.display = 'none';
            document.getElementById('banner-settings').style.display = 'none';
            document.getElementById('populer-settings').style.display = 'none';
            document.getElementById('marka-settings').style.display = 'none';

            if (type === 'banner') {
                document.getElementById('banner-settings').style.display = 'block';
            } else if (type === 'populer') {
                document.getElementById('populer-settings').style.display = 'block';
                loadAllProducts();
                loadPopularProducts();
            } else if (type === 'marka') {
                document.getElementById('marka-settings').style.display = 'block';
                loadBrands();
            }
        }

        function showMainSettings() {
            document.getElementById('banner-settings').style.display = 'none';
            document.getElementById('populer-settings').style.display = 'none';
            document.getElementById('marka-settings').style.display = 'none';
            document.getElementById('main-settings').style.display = 'grid';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('banner_image');
            const fileBox = document.querySelector('.file-upload-box');
            const selectedFile = document.querySelector('.selected-file');
            const bannerForm = document.getElementById('bannerForm');

            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    selectedFile.style.display = 'block';
                    selectedFile.textContent = `Seçilen dosya: ${this.files[0].name}`;
                    fileBox.style.borderColor = '#FF6B00';
                }
            });

            bannerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', 'add_banner');

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showAlert(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        loadBanners();
                        this.reset();
                        selectedFile.style.display = 'none';
                        fileBox.style.borderColor = '#ddd';
                    }
                })
                .catch(error => {
                    showAlert('Bir hata oluştu', 'error');
                    console.error('Error:', error);
                });
            });

            // Banner silme işlemi
            document.querySelector('.banner-grid').addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-button') || e.target.closest('.delete-button')) {
                    e.preventDefault();
                    const deleteButton = e.target.classList.contains('delete-button') ? 
                                       e.target : 
                                       e.target.closest('.delete-button');
                    const bannerId = deleteButton.dataset.id;
                    
                    if (confirm('Bu bannerı silmek istediğinize emin misiniz?')) {
                        const formData = new FormData();
                        formData.append('action', 'delete_banner');
                        formData.append('banner_id', bannerId);

                        fetch(window.location.href, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            showAlert(data.message, data.success ? 'success' : 'error');
                            if (data.success) {
                                loadBanners();
                            }
                        })
                        .catch(error => {
                            showAlert('Bir hata oluştu', 'error');
                            console.error('Error:', error);
                        });
                    }
                }
            });
        });

        function loadBanners() {
            const formData = new FormData();
            formData.append('action', 'get_banners');

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const bannerGrid = document.querySelector('.banner-grid');
                    bannerGrid.innerHTML = data.banners.map(banner => `
                        <div class="banner-item">
                            <img src="data:image/jpeg;base64,${banner.image}" alt="Banner">
                            ${banner.link ? `<div class="banner-link">${banner.link}</div>` : ''}
                            <button class="delete-button" data-id="${banner.id}">
                                <i class="fas fa-trash"></i> Sil
                            </button>
                        </div>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Bannerlar yüklenirken bir hata oluştu', 'error');
            });
        }

        // Alert gösterme fonksiyonu
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.textContent = message;
            alert.className = `alert ${type}`;
            alert.style.display = 'block';

            setTimeout(() => {
                alert.style.display = 'none';
            }, 3000);
        }

        // Tüm ürünleri yükleme
        function loadAllProducts() {
            const formData = new FormData();
            formData.append('action', 'get_all_products');

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('urun_select');
                    select.innerHTML = '<option value="">Ürün Seçin</option>';
                    data.products.forEach(product => {
                        select.innerHTML += `<option value="${product.id}">${product.name}</option>`;
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Popüler ürünleri yükleme
        function loadPopularProducts() {
            const formData = new FormData();
            formData.append('action', 'get_popular_products');

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const grid = document.getElementById('populer-grid');
                    grid.innerHTML = data.products.map(product => `
                        <div class="banner-item">
                            <img src="data:image/jpeg;base64,${product.image}" alt="${product.name}">
                            <div class="banner-link">${product.name}</div>
                            <button class="delete-button" onclick="deletePopularProduct(${product.populer_id})">
                                <i class="fas fa-trash"></i> Sil
                            </button>
                        </div>
                    `).join('');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Markaları yükleme
        function loadBrands() {
            const formData = new FormData();
            formData.append('action', 'get_brands');

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const grid = document.getElementById('marka-grid');
                    grid.innerHTML = data.brands.map(brand => `
                        <div class="banner-item">
                            <img src="data:image/jpeg;base64,${brand.image}" alt="${brand.name}">
                            <div class="banner-link">${brand.name}</div>
                            <button class="delete-button" onclick="deleteBrand(${brand.id})">
                                <i class="fas fa-trash"></i> Sil
                            </button>
                        </div>
                    `).join('');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // ... existing event listeners ...

            // Popüler ürün form submit
            const populerForm = document.getElementById('populerForm');
            populerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', 'add_popular_product');

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showAlert(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        loadPopularProducts();
                        this.reset();
                    }
                })
                .catch(error => {
                    showAlert('Bir hata oluştu', 'error');
                    console.error('Error:', error);
                });
            });

            // Marka form submit
            const markaForm = document.getElementById('markaForm');
            markaForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', 'add_brand');

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showAlert(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        loadBrands();
                        this.reset();
                    }
                })
                .catch(error => {
                    showAlert('Bir hata oluştu', 'error');
                    console.error('Error:', error);
                });
            });
        });

        function deletePopularProduct(populerId) {
            if (confirm('Bu ürünü popüler listesinden kaldırmak istediğinize emin misiniz?')) {
                const formData = new FormData();
                formData.append('action', 'delete_popular_product');
                formData.append('populer_id', populerId);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showAlert(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        loadPopularProducts();
                    }
                })
                .catch(error => {
                    showAlert('Bir hata oluştu', 'error');
                    console.error('Error:', error);
                });
            }
        }

        function deleteBrand(brandId) {
            if (confirm('Bu markayı silmek istediğinize emin misiniz?')) {
                const formData = new FormData();
                formData.append('action', 'delete_brand');
                formData.append('marka_id', brandId);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showAlert(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        loadBrands();
                    }
                })
                .catch(error => {
                    showAlert('Bir hata oluştu', 'error');
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html>
