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

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
            text-align: center;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <?php 
    $page = 'iletisim';
    include 'navbar.php'; 
    
    require_once 'db_user_connection.php';

    $message = '';
    $messageType = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Veritabanı bağlantısını al
            $db = Database::getInstance();
            $conn = $db->getConnection();

            // Form verilerini al ve temizle
            function cleanInput($data) {
                return htmlspecialchars(strip_tags(trim($data)));
            }

            $ad = cleanInput($_POST['name'] ?? '');
            $soyad = cleanInput($_POST['surname'] ?? '');
            $eposta = cleanInput($_POST['email'] ?? '');
            $konu = cleanInput($_POST['subject'] ?? '');
            $mesaj = cleanInput($_POST['message'] ?? '');

            // Validasyonlar
            if (empty($ad) || empty($soyad) || empty($eposta) || empty($konu) || empty($mesaj)) {
                throw new Exception('Lütfen tüm alanları doldurunuz!');
            }

            if (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Geçersiz e-posta formatı!');
            }

            if (!preg_match("/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/u", $ad) || !preg_match("/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/u", $soyad)) {
                throw new Exception('Ad ve soyad sadece harf içerebilir!');
            }

            // SQL sorgusu hazırla
            $sql = "INSERT INTO iletisim_formu (ad, soyad, eposta, konu, mesaj) VALUES (:ad, :soyad, :eposta, :konu, :mesaj)";
            $stmt = $conn->prepare($sql);

            // Parametreleri bind et
            $stmt->bindParam(':ad', $ad);
            $stmt->bindParam(':soyad', $soyad);
            $stmt->bindParam(':eposta', $eposta);
            $stmt->bindParam(':konu', $konu);
            $stmt->bindParam(':mesaj', $mesaj);

            // Sorguyu çalıştır
            if ($stmt->execute()) {
                $message = 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapılacaktır.';
                $messageType = 'success';
            } else {
                throw new Exception('Mesaj gönderilirken bir hata oluştu.');
            }

        } catch (Exception $e) {
            $message = $e->getMessage();
            $messageType = 'error';
            error_log("İletişim formu hatası: " . $e->getMessage());
        }
    }
    ?>

    <div class="content">
        <div class="contact-container">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo htmlspecialchars($messageType); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

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
                <form method="POST" onsubmit="return validateForm()">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Adınız" required minlength="2" maxlength="50" pattern="[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+" title="Sadece harf kullanabilirsiniz">
                    </div>
                    <div class="form-group">
                        <input type="text" name="surname" placeholder="Soyadınız" required minlength="2" maxlength="50" pattern="[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+" title="Sadece harf kullanabilirsiniz">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="E-posta Adresiniz" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Konu" required minlength="3" maxlength="100">
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Mesajınız" required minlength="10" maxlength="1000"></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Gönder</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
    function validateForm() {
        const name = document.querySelector('input[name="name"]').value.trim();
        const surname = document.querySelector('input[name="surname"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const subject = document.querySelector('input[name="subject"]').value.trim();
        const message = document.querySelector('textarea[name="message"]').value.trim();

        // Boş alan kontrolü
        if (!name || !surname || !email || !subject || !message) {
            alert('Lütfen tüm alanları doldurunuz.');
            return false;
        }

        // E-posta formatı kontrolü
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Lütfen geçerli bir e-posta adresi giriniz.');
            return false;
        }

        // Ad ve soyad kontrolü (sadece harf ve boşluk)
        const nameRegex = /^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/;
        if (!nameRegex.test(name) || !nameRegex.test(surname)) {
            alert('Ad ve soyad sadece harf içerebilir.');
            return false;
        }

        // Minimum uzunluk kontrolleri
        if (name.length < 2 || surname.length < 2) {
            alert('Ad ve soyad en az 2 karakter olmalıdır.');
            return false;
        }

        if (subject.length < 3) {
            alert('Konu en az 3 karakter olmalıdır.');
            return false;
        }

        if (message.length < 10) {
            alert('Mesaj en az 10 karakter olmalıdır.');
            return false;
        }

        return true;
    }
    </script>
</body>
</html> 