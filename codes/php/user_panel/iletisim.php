<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beydem Hırdavat - İletişim</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background: #f8f9fa;
        }

        .content {
            margin-top: 100px;
            padding: 20px;
            min-height: calc(100vh - 100px);
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .contact-title {
            color: #003366;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
            position: relative;
        }

        .contact-info {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .info-title {
            color: #003366;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        .info-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: #f8f9fa;
        }

        .info-item i {
            color: #ff6b00;
            font-size: 24px;
            min-width: 24px;
        }

        .info-content {
            flex: 1;
        }

        .info-content h3 {
            color: #003366;
            font-size: 16px;
            margin: 0 0 5px 0;
        }

        .info-content p {
            color: #666;
            font-size: 14px;
            margin: 0;
            line-height: 1.5;
        }

        .contact-form {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .form-title {
            color: #003366;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            color: #444;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ff6b00;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #999;
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: #ff6b00;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin: 0 auto;
        }

        .submit-btn:hover {
            background: #ff8533;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .contact-container {
                padding: 20px;
            }

            .contact-title {
                font-size: 20px;
                margin-bottom: 30px;
            }

            .info-item {
                padding: 12px;
            }

            .info-item i {
                font-size: 20px;
            }

            .form-group input,
            .form-group textarea {
                padding: 10px 12px;
            }
        }
    </style>
</head>
<body>
    <?php 
    $page = 'iletisim';
    include 'navbar.php'; 
    ?>

    <div class="content">
        <div class="contact-container">            
            <div class="contact-info">
                <h2 class="info-title">İletişim Bilgileri</h2>
                <div class="info-items">
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div class="info-content">
                            <h3>Telefon</h3>
                            <p>+905419276499</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div class="info-content">
                            <h3>E-posta</h3>
                            <p>kiralidogukan@gmail.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="info-content">
                            <h3>Adres</h3>
                            <p>Sırrıpaşa Mahallesi Çenedere Caddesi No:33 DERİNCE/KOCAELİ</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <h2 class="form-title">İletişim Formu</h2>
                <form action="iletisim_gonder.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Adınız" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="surname" placeholder="Soyadınız" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="E-posta Adresiniz" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Konu" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Mesajınız" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Gönder</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html> 