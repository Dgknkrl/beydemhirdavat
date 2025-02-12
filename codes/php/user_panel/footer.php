<style>
    .footer {
        background-color: #003366;
        color: white;
        padding: 40px 0;
        font-family: 'Montserrat', sans-serif;
        position: relative;
    }

    .footer-container {
        max-width: 1200px;
        margin: auto;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 30px;
        padding: 0 20px;
    }

    .footer-section {
        display: flex;
        flex-direction: column;
        padding: 0 20px;
    }

    .footer-section:first-child {
        padding-left: 0;
    }

    .footer-section:last-child {
        padding-right: 0;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
    }

    .footer-logo img {
        height: 45px;
        width: auto;
    }

    .footer-logo-text {
        color: white;
        font-size: 14px;
        font-weight: bold;
        line-height: 1.2;
        text-align: left;
    }

    .footer h3 {
        color: white;
        font-size: 16px;
        margin-bottom: 20px;
        font-weight: bold;
        position: relative;
        padding-bottom: 10px;
    }

    .footer h3::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 30px;
        height: 2px;
        background-color: #ff6b00;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: white;
        text-decoration: none;
        font-size: 14px;
        opacity: 0.8;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        opacity: 1;
        color: #ff6b00;
    }

    .footer-contact {
        margin-bottom: 15px;

        align-items: center;
        gap: 10px;
        color: #fff;
        font-size: 14px;
        opacity: 0.8;
        transition: color 0.3s ease;
    }

    .footer-contact:hover {
        opacity: 1;
        color: #ff6b00;
    }

    .footer-contact i {
        color: #ff6b00;
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    .newsletter-form {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .newsletter-form input {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        font-family: inherit;
    }

    .newsletter-form button {
        background: #ff6b00;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .newsletter-form button:hover {
        background: #ff8533;
    }

    .social-links {
        display: flex;
        gap: 15px;
        margin-top: 25px;
    }

    .social-links a {
        color: white;
        font-size: 18px;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        opacity: 1;
        color: #ff6b00;
        transform: translateY(-2px);
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
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .footer-whatsapp:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
    }

    .footer-whatsapp i {
        font-size: 20px;
    }

    @media (max-width: 992px) {
        .footer-container {
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .footer-section:first-child {
            grid-column: 1 / -1;
        }
        .footer-section {
            padding: 0;
            align-items: center;
            text-align: center;
        }
        .footer-contact {
            justify-content: center;
        }
        .footer-links {
            text-align: center;
        }
        .social-links {
            justify-content: center;
        }
        .footer h3::after {
            left: 50%;
            transform: translateX(-50%);
        }
    }

    @media (max-width: 576px) {
        .footer-container {
            grid-template-columns: 1fr;
        }
        .footer-whatsapp {
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
        }
        .footer {
            padding: 30px 0;
        }
    }
</style>

<footer class="footer">
    <div class="footer-container">
        <!-- Logo ve İletişim Bölümü -->
        <div class="footer-section">
            <div class="footer-logo">
                <img src="../../../images/user_panel/navbar/beydemhirdavat.png" alt="Beydem Hırdavat Logo">
                <span class="footer-logo-text">BEYDEM<br>MAKİNA VE HIRDAVAT</span>
            </div>
            <div class="footer-contact">
                <i class="fas fa-map-marker-alt"></i>
                <span>Sırrıpaşa, Çenedere Cd. no:33, 41900 Derince/Kocaeli</span>
            </div>
            <div class="footer-contact">
                <i class="fas fa-phone"></i>
                <span>+90 262 229 0 229</span>
            </div>
            <div class="footer-contact">
                <i class="fas fa-envelope"></i>
                <span>info@beydemhirdavat.com</span>
            </div>
            <div class="social-links">
                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
            </div>
        </div>

        <!-- Kurumsal Bölümü -->
        <div class="footer-section">
            <h3>KURUMSAL</h3>
            <ul class="footer-links">
                <li><a href="#">Hakkımızda</a></li>
                <li><a href="#">İletişim</a></li>
                <li><a href="#">Sıkça Sorulan Sorular</a></li>
            </ul>
        </div>

        <!-- Alışveriş Bölümü -->
        <div class="footer-section">
            <h3>ALIŞVERİŞ</h3>
            <ul class="footer-links">
                <li><a href="#">İptal ve İade Şartları</a></li>
                <li><a href="#">Taksit Seçenekleri</a></li>
                <li><a href="#">Garanti ve Servis</a></li>
                <li><a href="#">Teslimat</a></li>
            </ul>
        </div>
    </div>
</footer> 