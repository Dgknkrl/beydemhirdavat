<style>
    .footer {
        background: linear-gradient(135deg, #003366, #002347);
        color: white;
        padding: 60px 0 40px;
        font-family: 'Poppins', sans-serif;
        position: relative;
        margin-top: 50px;
    }

    .footer-container {
        max-width: 1200px;
        margin: auto;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 40px;
        padding: 0 20px;
    }

    .footer-section {
        display: flex;
        flex-direction: column;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        transition: transform 0.3s ease;
    }

    .footer-logo:hover {
        transform: translateY(-3px);
    }

    .footer-logo img {
        height: 50px;
        width: auto;
        filter: brightness(1.1);
    }

    .footer-logo-text {
        color: white;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.3;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .footer h3 {
        color: white;
        font-size: 18px;
        margin-bottom: 25px;
        font-weight: 600;
        position: relative;
        padding-bottom: 12px;
    }

    .footer h3::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #ff6b00, #ff8533);
        border-radius: 2px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 15px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .footer-links a:hover {
        color: #ff6b00;
        transform: translateX(5px);
    }

    .footer-links a::before {
        content: '→';
        opacity: 0;
        transition: all 0.3s ease;
    }

    .footer-links a:hover::before {
        opacity: 1;
    }

    .footer-contact {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 14px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .footer-contact:hover {
        color: #ff6b00;
        transform: translateX(5px);
    }

    .footer-contact i {
        color: #ff6b00;
        font-size: 18px;
        width: 20px;
        text-align: center;
    }

    .social-links {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .social-links a {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        background: #ff6b00;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(255, 107, 0, 0.3);
    }

    .footer-whatsapp {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: linear-gradient(135deg, #25d366, #128C7E);
        color: white;
        padding: 12px 28px;
        border-radius: 30px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        font-size: 15px;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .footer-whatsapp:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
    }

    .footer-whatsapp i {
        font-size: 22px;
    }

    /* Tablet için Medya Sorguları */
    @media (max-width: 992px) {
        .footer {
            padding: 50px 0 30px;
        }

        .footer-container {
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .footer-section:first-child {
            grid-column: 1 / -1;
        }

        .footer h3 {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .footer h3::after {
            width: 35px;
        }
    }

    /* Mobil için Medya Sorguları */
    @media (max-width: 576px) {
        .footer {
            padding: 40px 0 25px;
        }

        .footer-container {
            grid-template-columns: 1fr;
            gap: 25px;
            padding: 0 15px;
        }

        .footer-section {
            text-align: center;
            align-items: center;
        }

        .footer-logo {
            justify-content: center;
        }

        .footer h3 {
            text-align: center;
        }

        .footer h3::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer-contact {
            justify-content: center;
        }

        .footer-links a {
            justify-content: center;
        }

        .social-links {
            justify-content: center;
        }

        .footer-whatsapp {
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            font-size: 14px;
        }

        .footer-whatsapp i {
            font-size: 20px;
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
            <div class="footer-contact">
                <i class="fab fa-whatsapp"></i>
                <span>WhatsApp: +90 541 841 43 23</span>
            </div>
            <div class="social-links">
                <a href="#" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
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