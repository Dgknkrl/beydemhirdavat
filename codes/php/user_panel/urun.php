<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Detayı - Beydem Hırdavat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        *{
            box-sizing: border-box;
        }
        body{
            font-family: 'Montserrat', sans-serif;
            background-color: #FBFCFF;
            box-sizing: border-box;
        }
        .content {
            max-width: 1200px;
            margin: 250px auto;
        }

        .urun-detay {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            border-radius: 8px;
        }

        .urun-resimler {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 600px;
            flex-direction: column;
            gap: 30px;
        }

        .ana-resim {
            width: 550px;
            height: 500px;
            object-fit: contain;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);  
            background-color: #fff;
            border-radius: 20px;
        }

        .kucuk-resimler {
            object-fit: contain;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .kucuk-resim {
            width: 80px;
            height: 80px;
            object-fit: contain;    
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .kucuk-resim:hover {
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            scale: 1.1;
        }

        .urun-bilgileri {
            width: 600px;
            height: 500px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: space-between;
            justify-content: space-between;
            gap: 25px;
            background-color: #fff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .urun-baslik {
            font-size: 25px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }


        .urun-detay-tabs {
            margin-top: 200px;
            border-bottom: none;
        }

        .tab-buttons {
            display: flex;
            gap: 0px;
            margin-bottom: 50px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .tab-button {
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 6  00;
            color: #333;
            cursor: pointer;
            border: none;
            background: transparent;
            transition: all 0.2s;
            flex: 1;
            text-align: center;
        }
        .tab-button:hover {
            background:rgba(255, 106, 0, 0.06);
            color: #333;
        }

        .tab-button.active {
            color: #fff;
            background: #ff6b00;
            border: none;
        }

        .tab-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 100px auto;
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .ozellikler-tablo {
            width: 100%;
            border-collapse: collapse;
        }

        .ozellikler-tablo tr:nth-child(odd) {
            background: #f8f8f8;
        }

        .ozellikler-tablo td {
            border: 1px solid #eee;
        }

        .ozellikler-tablo td:first-child {
            font-weight: 600;
            width: 200px;
        }
        .aciklama-icerik {
            margin: auto 0px;
        }
        #yorumlar, #sorular {
            background-color: transparent;
            box-shadow: none;
        }
        .yorumlar-liste, .sorular-liste {
            display: flex;
            margin-top: 100px;
            flex-direction: column;
            gap: 30px;
            margin-bottom: 30px;
        }

        .yorum-kutusu, .soru-kutusu {
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .yorum-baslik, .soru-baslik {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #666;
        }

        .admin-cevap {
            margin-top: 15px;
            padding: 15px;
            background: #fff;
            border-left: 3px solid #003366;
        }

        .soru-form {
            margin-top: 30px;
            background: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
            width: calc(100%-20px);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .gonder-button {
            background: #003366;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .gonder-button:hover {
            background: #002244;
        }

        .whatsapp-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #25D366;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            gap: 10px;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .whatsapp-button:hover {
            background: #128C7E;
            transform: translateY(-2px);
        }

        .whatsapp-button i {
            font-size: 24px;
        }

        .yorum-form {
            margin-top: 30px;
        }

        .yorum-kutusu {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .yorum-baslik {
            margin-bottom: 10px;
        }

        .yorum-baslik strong {
            color: #333;
            font-size: 15px;
        }

        .yorum-tarih {
            color: #666;
            font-size: 13px;
        }

        .yorum-icerik {
            color: #444;
            line-height: 1.5;
        }

        .checkbox-group {
            position: absolute;
            top: 30px;
            right: 30px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group label {
            font-size: 16px;
            color: #666;
        }

        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #ff6b00;
        }

        .gonder-button {
            background: #ff6b00;
            color: white;
            padding: 12px 40px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            display: block;
            margin: 0 auto;
            font-family: 'Montserrat', sans-serif;
        }

        .gonder-button:hover {
            background: #e65100;
            transform: translateY(-2px);
        }

        /* Yorum ve soru listeleri */
        .yorum-kutusu, .soru-kutusu {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .yorum-baslik, .soru-baslik {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .yorum-baslik strong, .soru-baslik strong {
            color: #333;
            font-size: 15px;
            font-weight: 600;
        }

        .yorum-tarih, .soru-tarih {
            color: #999;
            font-size: 13px;
        }

        .yorum-icerik, .soru-icerik {
            color: #666;
            line-height: 1.6;
            font-size: 16px;
        }

        .soru-icerik .soru {
            margin-bottom: 20px;
        }

        .admin-cevap {
            margin-top: 20px;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
        }

        .admin-cevap strong {
            display: block;
            color: #ff6b00;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .admin-cevap p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Form başlıkları */
        .form-baslik {
            font-size: 18px;
            color: #333;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* Input placeholder rengi */
        ::placeholder {
            color: #999;
            font-family: 'Montserrat', sans-serif;
        }

        /* Özellikler tablosu için yeni stiller */
        .ozellikler-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 20px 0;
        }

        .ozellik-kutu {
            background: #fff;
            padding: 20px 25px;
            border-radius: 4px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 51, 102, 0.55);
            cursor: pointer;
        }
        .ozellik-kutu:hover {
            background:rgba(0, 51, 102, 0.06);
            color: #fff;
        }

        .ozellik-baslik {
            color: #003366;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .ozellik-deger {
            color: #333;
            font-size: 16px;
        }

        /* Form stilleri güncelleme */
        .form-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-group {
            position: static; /* absolute yerine static */
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f8f8f8;
            padding: 8px 15px;
            border-radius: 20px;
        }

        .checkbox-group label {
            font-size: 13px;
            color: #666;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #ff6b00;
            cursor: pointer;
        }

        /* Özellikler tab içeriği için yeni stil */
        .ozellikler-liste {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 20px;
        }

        .ozellik-satir {
            display: flex;
            background: #f8f8f8;
            padding: 12px 15px;
            border-radius: 4px;
            align-items: center;
        }

        .ozellik-label {
            color: #003366;
            font-weight: 600;
            font-size: 16px;
            width: 150px;
            flex-shrink: 0;
        }

        .ozellik-value {
            color: #444;
            font-size: 16px;
        }
        .comment-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        .comment-box label {
            font-size: 14px;
            color: #555;
            display: block;
            margin-top: 10px;
        }
        .comment-box input, .comment-box textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .comment-box textarea {
            height: 80px;
        }
        .comment-box .checkbox-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .comment-box .checkbox-container input {
            margin-right: 10px;
        }
        .comment-box button {
            width: 100%;
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .comment-box button:hover {
            background-color: #e65c00;
        }
        .yorum-formu {
            width: 100%;
            max-width: 1200px;
            margin: 2rem auto;
            background-color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            padding: 2rem;
        }

        .yorum-formu h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: #4B5563;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #FF6B00;
            box-shadow: 0 0 0 2px rgba(255, 107, 0, 0.1);
        }

        .switch-container {
            margin: 1.5rem 0;
        }

        .switch-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .switch-label input[type="checkbox"] {
            display: none;
        }

        .switch-custom {
            position: relative;
            width: 36px;
            height: 20px;
            background-color: #D1D5DB;
            border-radius: 20px;
            transition: background-color 0.2s;
        }

        .switch-custom:before {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: white;
            top: 2px;
            left: 2px;
            transition: transform 0.2s;
        }

        .switch-label input[type="checkbox"]:checked + .switch-custom {
            background-color: #FF6B00;
        }

        .switch-label input[type="checkbox"]:checked + .switch-custom:before {
            transform: translateX(16px);
        }

        .switch-text {
            color: #4B5563;
            font-size: 0.875rem;
        }

        .submit-button {
            width: 100%;
            background-color: #FF6B00;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submit-button:hover {
            background-color: #E65100;
            transform: translateY(-1px);
        }

        /* Tablet için */
        @media (max-width: 992px) {
            .yorum-formu {
                max-width: 95%;
                padding: 1.5rem;
            }
        }

        /* Mobil için */
        @media (max-width: 576px) {
            .yorum-formu {
                max-width: 100%;
                margin: 1rem auto;
                padding: 1rem;
                border-radius: 0.5rem;
            }
            
            .yorum-formu h2 {
                font-size: 1.125rem;
                margin-bottom: 1rem;
            }
            
            .form-group {
                margin-bottom: 1rem;
            }
        }

        /* Çok küçük ekranlar için */
        @media (max-width: 360px) {
            .yorum-formu {
                padding: 0.75rem;
            }
            
            .form-group label {
                font-size: 0.75rem;
            }
        }

        /* Başarı mesajı stili */
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 1200px;
            text-align: center;
            font-size: 16px;
            animation: fadeOut 5s forwards;
            position: relative;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* Tab içerik görünürlük ayarları */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Tab butonları stili güncelleme */
        .tab-button {
            background: #f5f5f5;
            color: #666;
        }

        .tab-button.active {
            background: #FF6B00;
            color: white;
        }
    </style>
</head>
<body>
    <?php
    $page = 'urun';
    include 'navbar.php';
    require_once '../includes/db_connection.php';

    $urun_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Ürün bilgilerini çek
    $urun_query = "SELECT u.*, r.resim1, r.resim2, r.resim3, r.resim4,
                   (SELECT GROUP_CONCAT(DISTINCT CONCAT(uo.ozellik_adi, ':', uo.ozellik_deger) SEPARATOR '||')
                    FROM urun_ozellik uo 
                    WHERE uo.urun_id = u.urun_id) as ozellikler
                   FROM urunler u 
                   LEFT JOIN resimler r ON u.urun_id = r.urun_id
                   WHERE u.urun_id = $urun_id";

    $urun_result = mysqli_query($conn, $urun_query);
    $urun = mysqli_fetch_assoc($urun_result);

    if (!$urun) {
        header('Location: urunler.php');
        exit;
    }

    // Özellikleri diziye çevir
    $ozellikler = [];
    if ($urun['ozellikler']) {
        foreach(explode('||', $urun['ozellikler']) as $ozellik) {
            list($adi, $deger) = explode(':', $ozellik);
            $ozellikler[$adi] = $deger;
        }
    }

    // Yorumları çek
    $yorumlar_query = "SELECT * FROM urun_yorumlari 
                       WHERE urun_id = ? AND yayin_durumu = 1 
                       ORDER BY olusturma_tarihi DESC";
    $stmt = mysqli_prepare($conn, $yorumlar_query);
    mysqli_stmt_bind_param($stmt, "i", $urun_id);
    mysqli_stmt_execute($stmt);
    $yorumlar_result = mysqli_stmt_get_result($stmt);
    $yorumlar = mysqli_fetch_all($yorumlar_result, MYSQLI_ASSOC);

    // Soruları çek
    $sorular_query = "SELECT * FROM satici_sorulari 
                      WHERE urun_id = ? AND yayin_durumu = 1 
                      ORDER BY olusturma_tarihi DESC";
    $stmt = mysqli_prepare($conn, $sorular_query);
    mysqli_stmt_bind_param($stmt, "i", $urun_id);
    mysqli_stmt_execute($stmt);
    $sorular_result = mysqli_stmt_get_result($stmt);
    $sorular = mysqli_fetch_all($sorular_result, MYSQLI_ASSOC);

    // Form başarı mesajı için session kontrolü
    session_start();
    $success_message = '';
    if (isset($_SESSION['form_success'])) {
        $success_message = $_SESSION['form_success'];
        unset($_SESSION['form_success']);
    }

    // Form gönderimlerini işle
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['form_type'])) {
            $ad = htmlspecialchars($_POST['ad']);
            $soyad = htmlspecialchars($_POST['soyad']);
            $eposta = htmlspecialchars($_POST['eposta']);
            
            if ($_POST['form_type'] == 'yorum') {
                $yorum = htmlspecialchars($_POST['yorum']);
                
                $yorum_query = "INSERT INTO urun_yorumlari (urun_id, ad, soyad, eposta, yorum, yayin_durumu, olusturma_tarihi) 
                               VALUES (?, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP)";
                $stmt = mysqli_prepare($conn, $yorum_query);
                mysqli_stmt_bind_param($stmt, "issss", $urun_id, $ad, $soyad, $eposta, $yorum);
                
                if(mysqli_stmt_execute($stmt)) {
                    $_SESSION['form_success'] = "Yorumunuz başarıyla gönderildi. Onaylandıktan sonra yayınlanacaktır.";
                    header("Location: ".$_SERVER['REQUEST_URI']);
                    exit;
                }
                
            } elseif ($_POST['form_type'] == 'soru') {
                $soru = htmlspecialchars($_POST['soru']);
                $isim_gorunsun_mu = isset($_POST['isim_gorunsun_mu']) ? 1 : 0;
                
                $soru_query = "INSERT INTO satici_sorulari (urun_id, ad, soyad, eposta, soru, isim_gorunsun_mu, yayin_durumu, olusturma_tarihi) 
                              VALUES (?, ?, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP)";
                $stmt = mysqli_prepare($conn, $soru_query);
                mysqli_stmt_bind_param($stmt, "issssi", $urun_id, $ad, $soyad, $eposta, $soru, $isim_gorunsun_mu);
                
                if(mysqli_stmt_execute($stmt)) {
                    $_SESSION['form_success'] = "Sorunuz başarıyla gönderildi. Onaylandıktan sonra yayınlanacaktır.";
                    header("Location: ".$_SERVER['REQUEST_URI']);
                    exit;
                }
            }
        }
    }
    ?>

    <div class="content">
        <div class="urun-detay">
            <div class="urun-resimler">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($urun['resim1']); ?>" 
                     alt="<?php echo $urun['urun_adi']; ?>" 
                     class="ana-resim" id="ana-resim">
                
                <div class="kucuk-resimler">
                    <?php for($i = 1; $i <= 4; $i++): 
                        if($urun['resim'.$i]): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($urun['resim'.$i]); ?>" 
                                 alt="<?php echo $urun['urun_adi']; ?>" 
                                 class="kucuk-resim"
                                 onclick="degistirResim(this)">
                    <?php endif; endfor; ?>
                </div>
            </div>

            <div class="urun-bilgileri">
                <h1 class="urun-baslik"><?php echo $urun['urun_adi']; ?></h1>
                
                <!-- Özellikler tablosu -->
                <div class="ozellikler-tablo">
                    <?php
                    // Ürün özelliklerini çek
                    $ozellikler_query = "SELECT ozellik_adi, ozellik_deger 
                                        FROM urun_ozellik 
                                        WHERE urun_id = $urun_id 
                                        ORDER BY ozellik_id ASC 
                                        LIMIT 6";
                    $ozellikler_result = mysqli_query($conn, $ozellikler_query);
                    ?>
                    <div class="ozellikler-grid">
                        <?php while($ozellik = mysqli_fetch_assoc($ozellikler_result)): ?>
                            <div class="ozellik-kutu">
                                <div class="ozellik-baslik"><?php echo htmlspecialchars($ozellik['ozellik_adi']); ?></div>
                                <div class="ozellik-deger"><?php echo htmlspecialchars($ozellik['ozellik_deger']); ?></div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <a href="https://api.whatsapp.com/send?phone=905555555555&text=<?php echo urlencode($urun['urun_adi']); ?> ürünü hakkında bilgi almak istiyorum" 
                   class="whatsapp-button" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                    FİYAT BİLGİSİ AL
                </a>
            </div>
        </div>

        <div class="urun-detay-tabs">
            <div class="tab-buttons">
                <div class="tab-button active" onclick="degistirTab(this, 'aciklama')">Ürün Açıklaması</div>
                <div class="tab-button" onclick="degistirTab(this, 'yorumlar')">Ürün Yorumları</div>
                <div class="tab-button" onclick="degistirTab(this, 'sorular')">Satıcıya Sor</div>
                <div class="tab-button" onclick="degistirTab(this, 'ozellikler')">Ürün Özellikleri</div>
            </div>

            <?php if($success_message): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
            <?php endif; ?>

            <div id="aciklama" class="tab-content active">
                <div class="aciklama-icerik">
                    <?php echo $urun['urun_aciklama'] ?? 'Ürün açıklaması bulunmamaktadır.'; ?>
                </div>
            </div>

            <div id="ozellikler" class="tab-content">
                <div class="ozellikler-liste">
                    <?php 
                    $ozellikler_query = "SELECT ozellik_adi, ozellik_deger 
                                        FROM urun_ozellik 
                                        WHERE urun_id = $urun_id 
                                        ORDER BY ozellik_id ASC";
                    $ozellikler_result = mysqli_query($conn, $ozellikler_query);
                    
                    while($ozellik = mysqli_fetch_assoc($ozellikler_result)): 
                    ?>
                        <div class="ozellik-satir">
                            <div class="ozellik-label"><?php echo htmlspecialchars($ozellik['ozellik_adi']); ?></div>
                            <div class="ozellik-value"><?php echo htmlspecialchars($ozellik['ozellik_deger']); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div id="yorumlar" class="tab-content">
                <div class="yorum-formu">
                    <h2>Yorum Yap</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="form_type" value="yorum">
                        <div class="form-group">
                            <input type="text" name="ad" placeholder="Adınızı girin" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" name="soyad" placeholder="Soyadınızı girin" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="email" name="eposta" placeholder="E-posta adresinizi girin" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <textarea name="yorum" placeholder="Yorumunuzu buraya yazın..." class="form-textarea" required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-button">Yorum Gönder</button>
                    </form>
                </div>
                
                <div class="yorumlar-liste">
                    <?php if (count($yorumlar) > 0): ?>
                        <?php foreach ($yorumlar as $yorum): ?>
                            <div class="yorum-kutusu">
                                <div class="yorum-baslik">
                                    <strong><?php echo htmlspecialchars($yorum['ad'] . ' ' . $yorum['soyad']); ?></strong>
                                    <span class="yorum-tarih"><?php echo date('d.m.Y', strtotime($yorum['olusturma_tarihi'])); ?></span>
                                </div>
                                <div class="yorum-icerik">
                                    <?php echo nl2br(htmlspecialchars($yorum['yorum'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Bu ürün için henüz yorum yapılmamış.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div id="sorular" class="tab-content">
                <div class="yorum-formu">
                    <h2>Satıcıya Sor</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="form_type" value="soru">
                        <div class="form-group">
                            <input type="text" name="ad" placeholder="Adınızı girin" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" name="soyad" placeholder="Soyadınızı girin" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="email" name="eposta" placeholder="E-posta adresinizi girin" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <textarea name="soru" placeholder="Sorunuzu buraya yazın..." class="form-textarea" required></textarea>
                        </div>
                        
                        <div class="switch-container">
                            <label class="switch-label">
                                <input type="checkbox" name="isim_gorunsun_mu" checked>
                                <span class="switch-custom"></span>
                                <span class="switch-text">İsmim paylaşılabilir</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="submit-button">Soru Gönder</button>
                    </form>
                </div>
                
                <div class="sorular-liste">
                    <?php if (count($sorular) > 0): ?>
                        <?php foreach ($sorular as $soru): ?>
                            <div class="soru-kutusu">
                                <div class="soru-baslik">
                                    <strong>
                                        <?php echo $soru['isim_gorunsun_mu'] ? 
                                            htmlspecialchars($soru['ad'] . ' ' . $soru['soyad']) : 
                                            'Misafir'; ?>
                                    </strong>
                                    <span class="soru-tarih"><?php echo date('d.m.Y', strtotime($soru['olusturma_tarihi'])); ?></span>
                                </div>
                                <div class="soru-icerik">
                                    <p class="soru"><?php echo nl2br(htmlspecialchars($soru['soru'])); ?></p>
                                    <?php if($soru['admin_cevabi']): ?>
                                        <div class="admin-cevap">
                                            <strong>Satıcı Cevabı:</strong>
                                            <p><?php echo nl2br(htmlspecialchars($soru['admin_cevabi'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Bu ürün için henüz soru sorulmamış.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function degistirResim(img) {
            const anaResim = document.getElementById('ana-resim');
            anaResim.src = img.src;
            
            // Tıklanan resme active class ekle, diğerlerinden kaldır
            document.querySelectorAll('.kucuk-resim').forEach(resim => {
                resim.classList.remove('active');
            });
            img.classList.add('active');
        }

        function degistirTab(button, tabId) {
            // Tüm tab butonlarından active sınıfını kaldır
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Tıklanan butona active sınıfını ekle
            button.classList.add('active');
            
            // Tüm tab içeriklerini gizle
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Seçilen tab içeriğini göster
            document.getElementById(tabId).classList.add('active');
        }

        // Başarı mesajını 5 saniye sonra kaldır
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.querySelector('.success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>

    <?php 
    mysqli_close($conn);
    include 'footer.php'; 
    ?>
</body>
</html>
