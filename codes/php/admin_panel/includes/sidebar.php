<?php
// Mevcut sayfayı belirle
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<style>
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

    .menu-group {
        margin-bottom: 10px;
    }

    .menu-group > .menu-item {
        margin-bottom: 0;
        border-radius: 8px 8px 0 0;
    }

    /* Ürün ayarları aktif olduğunda turuncu arka plan */
    .menu-group > .menu-item.active {
        background-color: #ff6b00;
        color: white;
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

    .menu-item i {
        margin-right: 10px;
        font-size: 18px;
    }

    .submenu {
        list-style: none;
        padding: 5px 0 5px 40px;
        margin: 0;
        background-color: #fff;
        border-radius: 0 0 8px 8px;
    }

    .submenu .menu-item {
        padding: 8px 15px;
        margin-bottom: 2px;
        color: #333;
    }

    .submenu .menu-item:hover,
    .submenu .menu-item.active {
        background-color: transparent;
        color: #ff6b00;
    }

    .menu-list > .menu-item {
        margin-top: 10px;
        color: #333;
    }

    .menu-list > .menu-item:hover,
    .menu-list > .menu-item.active {
        background-color: #ff6b00;
        color: white;
    }
</style>

<div class="sidebar">
    <h2 class="panel-title">PANEL AYARLARI</h2>
    <ul class="menu-list">
        <!-- Ana Sayfa Ayarları -->
        <a href="anasayfa_ayarlari.php" class="menu-item <?php echo $current_page === 'anasayfa_ayarlari' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            Ana Sayfa Ayarları
        </a>

        <!-- Mevcut Ürün Ayarları Grubu -->
        <li class="menu-group">
            <a href="urun_ayarlari.php" class="menu-item <?php echo in_array($current_page, ['urun_ayarlari', 'urun_ekle', 'urun_duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-box"></i>
                Ürün Ayarları
            </a>
            <ul class="submenu">
                <li>
                    <a href="urun_ayarlari.php" class="menu-item <?php echo $current_page === 'urun_ayarlari' ? 'active' : ''; ?>">
                        Ürünleri Görüntüle
                    </a>
                </li>
                <li>
                    <a href="urun_ekle.php" class="menu-item <?php echo $current_page === 'urun_ekle' ? 'active' : ''; ?>">
                        Ürün Ekle
                    </a>
                </li>
            </ul>
        </li>

        <!-- Diğer mevcut menü öğeleri -->
        <a href="kategori_ayarlari.php" class="menu-item <?php echo $current_page === 'kategori_ayarlari' ? 'active' : ''; ?>">
            <i class="fas fa-tags"></i>
            Kategori Ayarları
        </a>
        <a href="kataloglar_ayarlari.php" class="menu-item <?php echo $current_page === 'kataloglar_ayarlari' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i>
            Kataloglar
        </a>
        <a href="iletisim_kayitlari.php" class="menu-item <?php echo $current_page === 'iletisim_kayitlari' ? 'active' : ''; ?>">
            <i class="fas fa-address-book"></i>
            İletişim Kayıtları
        </a>
    </ul>
</div> 