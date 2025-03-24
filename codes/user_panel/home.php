<?php
include '../includes/db_connection.php';

// Ana kategorileri çek
$ana_kategori_query = "SELECT * FROM ana_kategori";
$ana_kategori_result = mysqli_query($conn, $ana_kategori_query);

// Banner verileri için sorgu
$banner_query = "SELECT * FROM dinamik_banner ORDER BY banner_id ASC";
$banner_result = mysqli_query($conn, $banner_query);

// Markaları çek
$marka_query = "SELECT * FROM markalar ORDER BY marka_id ASC";
$marka_result = mysqli_query($conn, $marka_query);
?>

<!-- Google Fonts - Montserrat -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Navbar -->
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-left">
            <a href="/" class="logo">
                <img src="../../../images/user_panel/navbar/beydemhirdavat.png" alt="Beydem Hırdavat">
            </a>
        </div>
        
        <div class="nav-center">
            <div class="search-box">
                <input type="text" placeholder="Aradığınız ürünü yazınız...">
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                    <span>Ara</span>
                </button>
            </div>
        </div>
        
        <div class="nav-right">
            <a href="https://wa.me/TELEFONNUMARASI" class="whatsapp-btn">
                <i class="fab fa-whatsapp"></i>
                WhatsApp'ta Sorun
            </a>
        </div>
    </div>
</nav>

<!-- Alt Menü -->
<div class="sub-nav">
    <div class="nav-container">
        <ul class="main-menu">
            <li><a href="index.php">ANASAYFA</a></li>
            <li><a href="urunler.php">ÜRÜNLER</a></li>
            <li><a href="kataloglar.php">KATALOGLAR</a></li>
            <li><a href="iletisim.php">İLETİŞİM</a></li>
        </ul>
    </div>
</div>

<!-- Kategori Bölümü -->
<div class="container">
    <div class="category-nav">
        <?php while($ana_kategori = mysqli_fetch_assoc($ana_kategori_result)) { ?>
            <div class="category-item">
                <div class="category-icon">
                    <?php if ($ana_kategori['resim'] && file_exists("../" . $ana_kategori['resim'])) { ?>
                        <img src="../<?php echo htmlspecialchars($ana_kategori['resim']); ?>" 
                             alt="<?php echo htmlspecialchars($ana_kategori['ana_kategori_adi']); ?>">
                    <?php } else { ?>
                        <i class="fas fa-folder"></i>
                    <?php } ?>
                </div>
                
                <?php
                $alt_kategori_query = "SELECT * FROM alt_kategori WHERE ana_kategori_id = " . $ana_kategori['ana_kategori_id'];
                $alt_kategori_result = mysqli_query($conn, $alt_kategori_query);
                
                if(mysqli_num_rows($alt_kategori_result) > 0) { ?>
                    <div class="subcategory-popup">
                        <div class="popup-header">
                            <?php echo $ana_kategori['ana_kategori_adi']; ?>
                        </div>
                        <div class="subcategory-grid">
                            <?php while($alt_kategori = mysqli_fetch_assoc($alt_kategori_result)) { ?>
                                <div class="subcategory-item">
                                    <?php echo $alt_kategori['alt_kategori_adi']; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Banner Slider -->
<div class="banner-slider">
    <div class="swiper bannerSwiper">
        <div class="swiper-wrapper">
            <?php while($banner = mysqli_fetch_assoc($banner_result)) { ?>
                <div class="swiper-slide">
                    <a href="<?php echo htmlspecialchars($banner['link']); ?>">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($banner['banner_gorsel']); ?>" 
                             alt="Banner">
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <div class="swiper-pagination"></div>
</div>

<!-- Markalar Bölümü (Güncellenen kısım) -->
<div class="brands-section">
    <div class="section-title">
        <h2>MARKALARIMIZ</h2>
    </div>
    <div class="brands-grid">
        <?php while($marka = mysqli_fetch_assoc($marka_result)) { ?>
            <div class="brand-card">
                <a href="/marka/<?php echo $marka['marka_id']; ?>">
                    <div class="brand-image">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($marka['marka_gorsel']); ?>" 
                             alt="<?php echo htmlspecialchars($marka['marka_adi']); ?>">
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Tab Sistemi -->
<div class="tab-section">
    <div class="tab-container">
        <div class="tab-buttons">
            <button class="tab-btn active" data-tab="siparisler">
                <i class="fas fa-shopping-cart"></i>
                <span>SİPARİŞLER</span>
            </button>
            <button class="tab-btn" data-tab="ulasim">
                <i class="fas fa-truck"></i>
                <span>ULAŞIM</span>
            </button>
            <button class="tab-btn" data-tab="iade">
                <i class="fas fa-exchange-alt"></i>
                <span>İADE & DEĞİŞİM</span>
            </button>
            <button class="tab-btn" data-tab="urunler">
                <i class="fas fa-box"></i>
                <span>ÜRÜNLER</span>
            </button>
        </div>

        <div class="tab-content">
            <div class="tab-pane active" id="siparisler">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-title">Telefondan sipariş verebilir miyim?</div>
                        <div class="info-text">Evet, müşteri hizmetlerimizi arayarak sipariş verebilirsiniz.</div>
                    </div>
                    <div class="info-item">
                        <div class="info-title">Havale ile ödemelerde indirim geçerli mi?</div>
                        <div class="info-text">Evet, havale/EFT ödemelerinde %5 indirim uygulanmaktadır.</div>
                    </div>
                    <div class="info-item">
                        <div class="info-title">Minimum sipariş tutarı var mı?</div>
                        <div class="info-text">Hayır, dilediğiniz tutarda sipariş verebilirsiniz.</div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="ulasim">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-title">Kargo Takibi</div>
                        <div class="info-text">Siparişiniz kargoya verildiğinde SMS ile bilgilendirilirsiniz.</div>
                    </div>
                    <div class="info-item">
                        <div class="info-title">Teslimat Süresi</div>
                        <div class="info-text">Siparişleriniz 1-3 iş günü içerisinde teslim edilmektedir.</div>
                    </div>
                    <div class="info-item">
                        <div class="info-title">Kargo Ücreti</div>
                        <div class="info-text">300 TL üzeri siparişlerde kargo ücretsizdir.</div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="iade">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-title">İade Koşulları</div>
                        <div class="info-text">14 gün içerisinde iade hakkınız bulunmaktadır.</div>
                    </div>
                    <div class="info-item">
                        <div class="info-title">Değişim Süreci</div>
                        <div class="info-text">Ürün değişimi için müşteri hizmetleri ile iletişime geçebilirsiniz.</div>
                    </div>
                    <div class="info-item">
                        <div class="info-title">İade Kargo Ücreti</div>
                        <div class="info-text">Ayıplı ürün iadelerinde kargo ücreti firmamıza aittir.</div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="urunler">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-title">Ürün Garantisi</div>
                        <div class="info-text">Tüm ürünlerimiz 2 yıl garantilidir.</div>
                    </div>
                    <div class="info-item">
                        <div class="info-title">Ürün Kalitesi</div>
                        <div class="info-text">Satışını yaptığımız tüm ürünler %100 orijinaldir.</div>
                    </div>
                    <div class="info-item">
                        <div class="info-title">Stok Durumu</div>
                        <div class="info-text">Sitede gördüğünüz tüm ürünler stok mevcuttur.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
body {
    background-color: #FBFCFF;
    margin: 0;
    padding: 0;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;
}

/* Navbar Stilleri */
.navbar {
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    padding: 15px 0;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-left .logo img {
    height: 50px;
    width: auto;
}

.nav-center {
    flex: 1;
    max-width: 800px;
    margin: 0 40px;
}

.search-box {
    display: flex;
    align-items: center;
    border: 2px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.search-box:focus-within {
    border-color: #003E90;
    box-shadow: 0 0 0 3px rgba(0, 62, 144, 0.1);
}

.search-box input {
    flex: 1;
    padding: 15px 20px;
    border: none;
    outline: none;
    font-size: 15px;
    color: #333;
}

.search-box input::placeholder {
    color: #999;
}

.search-btn {
    background: #003E90;
    color: white;
    border: none;
    padding: 15px 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-btn i {
    font-size: 18px;
}

.search-btn:hover {
    background: #002d6b;
}

.whatsapp-btn {
    background: #25D366;
    color: white;
    padding: 12px 24px;
    border-radius: 5px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: background 0.3s;
}

.whatsapp-btn:hover {
    background: #1fad54;
}

/* Alt Menü Stilleri */
.sub-nav {
    background: #003E90;
    padding: 15px 0;
}

.main-menu {
    list-style: none;
    display: flex;
    gap: 30px;
    justify-content: flex-start;
}

.main-menu a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: opacity 0.3s;
}

.main-menu a:hover {
    opacity: 0.8;
}

/* Container düzenlemesi */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
}

.category-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
    padding: 10px 0; /* Yüksekliği azalttık */
    margin-top: 0; /* Sub-nav'a yapıştırdık */
    height: 43px; /* Icon + 3px */
}

/* Icon düzenlemesi */
.category-item {
    position: static;
    cursor: pointer;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.category-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter: brightness(0); /* Siyah icon */
    transition: all 0.3s ease;
}

.category-item:hover .category-icon img {
    filter: brightness(1); /* Hover'da orijinal renk */
}

/* Popup düzenlemesi */
.subcategory-popup {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    margin-top: 20px;
}

/* Popup içerik düzeni */
.popup-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    font-weight: 600;
    color: #003E90;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 0.5px;
    text-align: center;
}

.subcategory-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    padding: 15px;
}

/* Hover durumunda popup görünürlüğü */
.category-item:hover .subcategory-popup {
    opacity: 1;
    visibility: visible;
}

/* Responsive düzenlemeler */
@media (max-width: 1280px) {
    .container {
        padding: 20px;
    }
    
    .subcategory-popup {
        width: 100%;
        left: 0;
    }
}

@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        gap: 15px;
    }
    
    .nav-center {
        max-width: 100%;
        margin: 15px 0;
    }
    
    .main-menu {
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .whatsapp-btn {
        width: 100%;
        justify-content: center;
    }
    
    .category-nav {
        height: auto;
        padding: 15px 0;
    }
    
    .category-icon {
        width: 35px; /* Mobilde daha küçük */
        height: 35px;
    }
    
    .subcategory-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .search-box input {
        padding: 12px 15px;
    }
    
    .search-btn {
        padding: 12px 20px;
    }
}

body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .back-button {
            background: #ff6600;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        .back-button:hover {
            background: #e65c00;
        }
        h1 {
            font-size: 22px;
            color: #333;
        }
        .upload-section {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .upload-section input {
            margin-top: 10px;
        }
        .button {
            background: #ff6600;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        .button:hover {
            background: #e65c00;
        }
        .banner-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .banner-item {
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        .banner-item img {
            width: 100%;
            display: block;
        }
        .banner-actions {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }
        .banner-actions button {
            background: #ff3300;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .banner-actions button:hover {
            background: #cc2900;
        }
        /* Banner Slider Styles */
        .banner-slider {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            position: relative;
            z-index: 1;
            margin-top: 150px; /* Slider ile category-nav arası boşluk */
        }
        .bannerSwiper {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .swiper-slide {
            width: 100%;
            height: 100%;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        /* Pagination stilleri */
        .swiper-pagination {
            position: relative !important;
            bottom: 0 !important;
            margin-top: 15px;
        }
        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: #003E90;
            opacity: 0.3;
            margin: 0 5px !important;
            transition: all 0.3s ease;
        }
        .swiper-pagination-bullet-active {
            opacity: 1;
            background: #003E90;
            width: 30px;
            border-radius: 6px;
        }
        /* Navigasyon butonları */
        .swiper-button-next,
        .swiper-button-prev {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            color: #003E90;
            transition: all 0.3s ease;
            z-index: 50;
        }
        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 20px;
            font-weight: bold;
        }
        .swiper-button-next {
            right: 20px;
        }
        .swiper-button-prev {
            left: 20px;
        }
        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: #fff;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        /* Responsive düzenlemeler */
        @media (max-width: 768px) {
            .bannerSwiper {
                height: 300px;
                margin-bottom: 20px;
            }
            
            .swiper-pagination {
                margin-top: 10px;
            }
        }

/* Markalar Bölümü (Güncellenen kısım) */
.brands-section {
    max-width: 1200px;
    margin: 60px auto;
    padding: 40px 20px;
    margin-top: 300px; /* Slider ile markalar arası boşluk */
}

.section-title {
    text-align: center;
    margin-bottom: 40px;
}

.section-title h2 {
    font-size: 28px;
    color: #333;
    font-weight: 600;
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
}

.section-title h2:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: #003E90;
}

.brands-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.brand-card {
    transition: all 0.3s ease;
    height: 340px; /* Sabit yükseklik */
    width: 570px; /* Sabit genişlik */
    margin: 0 auto;
}

.brand-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.brand-image img {
    width: 570px;
    height: 340px;
    object-fit: cover;
    filter: grayscale(100%);
    transition: all 0.3s ease;
    border-radius: 8px;
}

.brand-card:hover .brand-image img {
    filter: grayscale(0%);
    transform: scale(1.02);
}

.brand-title {
    font-size: 18px;
    color: #333;
    font-weight: 600;
    text-align: center;
    margin-top: 15px;
}

.show-more {
    text-align: center;
    margin-top: 40px;
}

.show-more-btn {
    display: inline-block;
    padding: 15px 40px;
    background: #003E90;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,62,144,0.2);
}

.show-more-btn:hover {
    background: #002d6b;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,62,144,0.3);
}

@media (max-width: 1200px) {
    .brand-card {
        width: 100%;
        height: auto;
        aspect-ratio: 570/340;
    }
    
    .brand-image img {
        width: 100%;
        height: 100%;
    }
}

@media (max-width: 768px) {
    .brands-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

/* Tab Sistemi Stilleri */
.tab-section {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
    margin-top: 300px; /* Markalar ile tab sistemi arası boşluk */
    margin-bottom: 300px; /* Tab sistemi ile footer arası boşluk */
}

.tab-container {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.tab-buttons {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 30px;
}

.tab-btn {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 15px;
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-btn i {
    font-size: 22px;
    color: #FF6B00;
}

.tab-btn span {
    font-size: 13px;
    font-weight: 600;
    color: #333;
}

.tab-btn:hover {
    background: #fff5eb;
}

.tab-btn.active {
    background: #FF6B00;
    border-color: #FF6B00;
}

.tab-btn.active i,
.tab-btn.active span {
    color: white;
}

.tab-content {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    border: 1px solid #eee;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

/* Her tab için özel içerik stilleri */
.info-list {
    display: grid;
    gap: 20px;
}

.info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #FF6B00;
}

.info-title {
    font-weight: 600;
    color: #FF6B00;
    margin-bottom: 8px;
}

.info-text {
    color: #666;
    font-size: 14px;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .brands-section,
    .tab-section {
        margin-top: 150px;
    }

    .tab-container {
        padding: 25px;
    }

    .tab-buttons {
        margin-bottom: 25px;
    }

    .tab-btn {
        padding: 15px;
    }

    .tab-content {
        padding: 20px;
    }

    .tab-section {
        margin-bottom: 150px;
    }
}
</style>

<script>
var swiper = new Swiper(".bannerSwiper", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});

// Tab sistemi için JavaScript
document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', () => {
        // Aktif tab butonunu güncelle
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        // Aktif içeriği göster
        const tabId = button.getAttribute('data-tab');
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
    });
});
</script>

<!-- En alta footer'ı ekliyoruz -->
<?php include 'footer.php'; ?>
