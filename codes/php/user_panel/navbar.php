<?php
// Aktif sayfayı belirlemek için $page değişkeni kullanılacak
// Ana sayfada şu şekilde kullanılacak: $page = 'anasayfa';
?>
<style>
    :root {
        --primary-color: #003366;
        --hover-color: #004080;
        --text-color: #003366;
        --active-color: #ff6b00;
    }

    @font-face {
        font-family: 'Montserrat';
        src: url('../../fonts/Montserrat-Regular.ttf') format('truetype');
        font-weight: normal;
    }

    @font-face {
        font-family: 'Montserrat';
        src: url('../../fonts/Montserrat-Bold.ttf') format('truetype');
        font-weight: bold;
    }

    *{
        margin: 0px;
        padding: 0px;
    }
    .navbar {
        background-color: #ffffff;
        padding: 20px 10px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin: 0px;
        transition: transform 0.3s ease;
    }

    .navbar--hidden {
        transform: translateY(-100%);
    }

    .nav-container {
        max-width: 1200px;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-direction: row;
        font-family: 'Montserrat', sans-serif;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo-container a {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .logo-container img {
        height: 45px;
        width: auto;
        margin-right: 10px;
    }

    .logo-text {
        color: var(--primary-color);
        font-weight: bold;
        font-size: 16px;
        line-height: 1.2;
        text-decoration: none;
        letter-spacing: 0.5px;
    }

    .nav-menu {
        display: flex;
        gap: 40px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        color: var(--text-color);
        text-decoration: none;
        font-size: 15px;
        font-weight: bold;
        padding: 10px 0;
        transition: all 0.3s ease;
        position: relative;
        letter-spacing: 0.3px;
    }

    .nav-link:hover {
        color: var(--active-color);
    }

    .nav-link:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        background: var(--active-color);
        left: 0;
        bottom: 0;
        transition: width 0.3s ease;
    }

    .nav-link:hover:after {
        width: 100%;
    }

    .nav-link.active {
        color: var(--active-color);
    }

    .whatsapp-btn {
        background-color: #25d366;
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(37, 211, 102, 0.3);
    }

    .whatsapp-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(37, 211, 102, 0.4);
    }

    .whatsapp-btn i {
        font-size: 18px;
    }

    .footer-whatsapp {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #25d366;
        color: white;
        padding: 12px 25px;
        border-radius: 30px;
        text-decoration: none;
        display: none;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
        opacity: 0;
        transform: translateY(20px);
    }

    .footer-whatsapp.show {
        display: flex;
        opacity: 1;
        transform: translateY(0);
    }

    .menu-toggle {
        display: none;
        cursor: pointer;
        font-size: 24px;
        color: var(--text-color);
    }

    .floating-whatsapp {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #25d366;
        color: white;
        padding: 12px 25px;
        border-radius: 30px;
        text-decoration: none;
        display: none;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
        opacity: 0;
        transform: translateY(20px);
    }

    .floating-whatsapp.show {
        display: flex;
        opacity: 1;
        transform: translateY(0);
    }

    .floating-whatsapp:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        color: white;
    }

    .floating-whatsapp i {
        font-size: 20px;
    }

    @media (max-width: 1024px) {
        .nav-container {
            flex-wrap: wrap;
            gap: 20px;
            padding: 0 15px;
        }

        .logo-container {
            flex: 1;
        }

        .menu-toggle {
            display: block;
            margin-left: auto;
        }

        .nav-menu {
            flex-basis: 100%;
            display: none;
            gap: 0;
            background: #ffffff;
            padding: 10px 0;
            border-top: 1px solid #eee;
        }

        .nav-menu.active {
            display: flex;
            flex-direction: column;
        }

        .nav-item {
            width: 100%;
            border-bottom: 1px solid #eee;
        }

        .nav-link {
            display: block;
            padding: 15px 0;
        }

        .whatsapp-btn {
            margin: 15px auto;
            order: 3;
        }
    }

    @media (max-width: 768px) {
        .navbar {
            position: relative;
            padding: 15px 10px;
        }

        .main-content {
            padding-top: 0 !important;
        }

        .logo-container img {
            height: 35px;
        }

        .logo-text {
            font-size: 14px;
        }

        .nav-link {
            font-size: 14px;
            padding: 12px 0;
        }

        .footer-whatsapp {
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
        }

        .floating-whatsapp {
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
        }
    }
</style>

<nav class="navbar">
    <div class="nav-container">
        <div class="logo-container">
            <a href="index.php">
                <img src="../../../images/user_panel/navbar/beydemhirdavat.png" alt="Beydem Hırdavat Logo">
                <span class="logo-text">BEYDEM<br>MAKİNA VE HIRDAVAT</span>
            </a>
        </div>

        <div class="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?php echo $page == 'anasayfa' ? 'active' : ''; ?>">ANASAYFA</a>
            </li>
            <li class="nav-item">
                <a href="urunler.php" class="nav-link <?php echo $page == 'urunler' ? 'active' : ''; ?>">ÜRÜNLER</a>
            </li>
            <li class="nav-item">
                <a href="kataloglar.php" class="nav-link <?php echo $page == 'kataloglar' ? 'active' : ''; ?>">KATALOGLAR</a>
            </li>
            <li class="nav-item">
                <a href="iletisim.php" class="nav-link <?php echo $page == 'iletisim' ? 'active' : ''; ?>">İLETİŞİM</a>
            </li>
        </ul>

        <a href="https://wa.me/905XXXXXXXXX" class="whatsapp-btn" target="_blank">
            <i class="fab fa-whatsapp"></i>
            Bize Ulaşın
        </a>
    </div>
</nav>

<a href="https://wa.me/905XXXXXXXXX" class="floating-whatsapp" target="_blank">
    <i class="fab fa-whatsapp"></i>
    Bizimle İletişime Geçin
</a>

<script>
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');
    const floatingWhatsapp = document.querySelector('.floating-whatsapp');

    window.addEventListener('scroll', () => {
        // Ekran genişliğini kontrol et
        if (window.innerWidth > 768) {
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                navbar.classList.remove('navbar--hidden');
                floatingWhatsapp.classList.remove('show');
                return;
            }

            if (currentScroll > lastScroll && !navbar.classList.contains('navbar--hidden')) {
                // Aşağı scroll
                navbar.classList.add('navbar--hidden');
                floatingWhatsapp.classList.add('show');
            } else if (currentScroll < lastScroll && navbar.classList.contains('navbar--hidden')) {
                // Yukarı scroll
                navbar.classList.remove('navbar--hidden');
                floatingWhatsapp.classList.remove('show');
            }

            lastScroll = currentScroll;
        } else {
            // 768px ve altında navbar ve whatsapp butonu her zaman görünür olsun
            navbar.classList.remove('navbar--hidden');
            floatingWhatsapp.classList.add('show');
        }
    });

    // Sayfa yüklendiğinde de kontrol et
    window.addEventListener('load', () => {
        if (window.innerWidth <= 768) {
            navbar.classList.remove('navbar--hidden');
            floatingWhatsapp.classList.add('show');
        }
    });

    // Ekran boyutu değiştiğinde de kontrol et
    window.addEventListener('resize', () => {
        if (window.innerWidth <= 768) {
            navbar.classList.remove('navbar--hidden');
            floatingWhatsapp.classList.add('show');
        }
    });

    document.querySelector('.menu-toggle').addEventListener('click', function() {
        document.querySelector('.nav-menu').classList.toggle('active');
    });
</script>
