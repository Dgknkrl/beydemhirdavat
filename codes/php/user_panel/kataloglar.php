<?php
require_once 'db_user_connection.php';

// Katalogları çek
try {
    $db = Database::getInstance()->getConnection();
    $sql = "SELECT * FROM kataloglar ORDER BY olusturma_tarihi DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $kataloglar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Navbar için aktif sayfayı belirle
$page = 'kataloglar';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beydem Hırdavat - Kataloglar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .main-content {
            min-height: 100vh;
            padding: 80px 20px 0 20px; /* Navbar için üstten padding */
            display: flex;
            flex-direction: column;
            box-sizing: border-box; /* padding'in yüksekliğe dahil olması için */
        }

        .katalog-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 sütunlu grid */
            gap: 50px;
            max-width: 1000px; /* Maksimum genişliği azalttık */
            margin: 20px auto;
            padding: 15px;
        }

        .katalog-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.1);
            overflow: visible;
            height: 100%;
            transition: all 0.5s ease;
            display: flex;
            flex-direction: column;
            position: relative;
            padding: 0px 20px;
        }

        .katalog-images {
            position: relative;
            width: 100%;
            padding-top: 100%; /* Orijinal boyutu koruyoruz */
            margin-bottom: 25px;
            overflow: hidden;
        }

        .katalog-page {
            position: absolute;
            top: 50%;
            height: 90%; /* Orijinal boyutu koruyoruz */
            width: 70%; /* Orijinal boyutu koruyoruz */
            transition: all 0.6s ease;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-radius: 8px;
            transform: translateY(-50%); /* Dikey merkezleme için */
        }

        .katalog-page img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Sol sayfa */
        .katalog-page:nth-child(1) {
            left: -35%;
            z-index: 1;
            filter: brightness(0.9);
            width: 60%;
            height: 80%;
            transform: translateY(-50%);
        }

        /* Orta sayfa */
        .katalog-page:nth-child(2) {
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            width: 70%;
            height: 90%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        /* Sağ sayfa */
        .katalog-page:nth-child(3) {
            right: -35%;
            z-index: 1;
            filter: brightness(0.9);
            width: 60%;
            height: 80%;
            transform: translateY(-50%);
        }

        /* Hover efektleri */
        .katalog-page:hover {
            z-index: 3;
            filter: brightness(1.1);
        }

        /* Sayfa içeriği için stil */
        .page-content {
            padding: 20px;
            color: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .bosch-logo {
            font-size: 18px;
            font-weight: bold;
            color: white;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin: 15px 0;
        }

        .page-footer {
            font-size: 12px;
            opacity: 0.8;
        }

        /* Kart bilgi alanı */
        .katalog-info {
            padding: 20px 10px;
            background: white;
            border-radius: 8px;
            z-index: 4;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .katalog-title {
            color: #003366;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .katalog-period {
            color: #005691;
            font-size: 14px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .katalog-description {
            color: #555;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .view-btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #ff6b00,rgb(252, 137, 55));
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0,86,145,0.2);
            margin: 0 auto;
        }

        .view-btn:hover {
            background: linear-gradient(135deg, #003d6b, #002d4f);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,86,145,0.3);
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            color: #333;
            margin: 30px 0 40px;
            flex-shrink: 0;
            position: relative;
            font-weight: 700;
            padding-bottom: 15px;
            font-family: 'Poppins', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 10px;
            background: #ff6b00;
            border-radius: 50%;
        }

        .section-title::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #ff6b00, #ff9248);
            border-radius: 2px;
        }

        .no-image {
            width: 100%;
            height: 100%;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #999;
        }

        .page-content {
            position: relative;
            height: 100%;
            z-index: 2;
        }

        .page-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .page-overlay {
            position: relative;
            z-index: 3;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(
                to bottom,
                rgba(0, 54, 102, 0.8),
                rgba(0, 54, 102, 0.6)
            );
            border-radius: 8px;
        }

        /* Mobil cihazlar için medya sorguları */
        @media screen and (max-width: 1024px) {
            .katalog-container {
                max-width: 900px;
                gap: 30px;
                padding: 10px;
            }

            .katalog-card {
                padding: 0px 15px;
            }
        }

        @media screen and (max-width: 768px) {
            .main-content {
                padding: 60px 10px 0 10px;
            }

            .katalog-container {
                grid-template-columns: 1fr; /* Tek kolon */
                max-width: 500px;
                gap: 30px;
            }

            .section-title {
                font-size: 28px;
                margin: 20px 0 30px;
            }

            .katalog-title {
                font-size: 16px;
            }

            .katalog-description {
                font-size: 14px;
            }

            .view-btn {
                padding: 10px 20px;
                font-size: 13px;
            }
        }

        @media screen and (max-width: 480px) {
            .katalog-container {
                gap: 20px;
                padding: 10px;
            }

            .section-title {
                font-size: 24px;
            }

            .katalog-title {
                font-size: 15px;
            }

            .katalog-description {
                font-size: 13px;
            }

            .katalog-period {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <h1 class="section-title">Kataloglarımız</h1>

        <div class="katalog-container">
                <?php foreach($kataloglar as $katalog): ?>
                    <div class="katalog-card">
                        <div class="katalog-images">
                            <?php 
                            $images = [
                                ['src' => $katalog['resim1'], 'position' => 'left'],
                                ['src' => $katalog['resim2'], 'position' => 'center'],
                                ['src' => $katalog['resim3'], 'position' => 'right']
                            ];
                            foreach($images as $index => $image): 
                                if($image['src']):
                            ?>
                                <div class="katalog-page" 
                                     data-position="<?= $image['position'] ?>" 
                                     onclick="changePosition(this)">
                                    <img src="data:image/jpeg;base64,<?= base64_encode($image['src']) ?>" 
                                         alt="<?= htmlspecialchars($katalog['isim']) ?>">
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        <div class="katalog-info">
                            <h2 class="katalog-title"><?= htmlspecialchars($katalog['isim']) ?></h2>
                            <div class="katalog-period">
                                <i class="far fa-calendar-alt"></i>
                                <?= htmlspecialchars($katalog['tarih']) ?>
                            </div>
                            <p class="katalog-description">
                                <?= htmlspecialchars($katalog['kisa_aciklama']) ?>
                            </p>
                            <a href="katalog_detay.php?id=<?= $katalog['katalog_id'] ?>" class="view-btn">
                                İncele
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
    </div>
<div>
    <?php include 'footer.php'; ?>
</body>
</html>

<script>
function changePosition(clickedElement) {
    const container = clickedElement.parentElement;
    const pages = container.getElementsByClassName('katalog-page');
    const clickedPosition = clickedElement.dataset.position;
    
    // Tüm sayfaların mevcut pozisyonlarını al
    const positions = Array.from(pages).map(page => page.dataset.position);
    
    // Yeni pozisyonları belirle
    if (clickedPosition === 'left') {
        // Sol tıklandığında: sol->orta, orta->sağ, sağ->sol
        Array.from(pages).forEach(page => {
            if (page.dataset.position === 'left') page.dataset.position = 'center';
            else if (page.dataset.position === 'center') page.dataset.position = 'right';
            else if (page.dataset.position === 'right') page.dataset.position = 'left';
        });
    } else if (clickedPosition === 'right') {
        // Sağ tıklandığında: sağ->orta, orta->sol, sol->sağ
        Array.from(pages).forEach(page => {
            if (page.dataset.position === 'right') page.dataset.position = 'center';
            else if (page.dataset.position === 'center') page.dataset.position = 'left';
            else if (page.dataset.position === 'left') page.dataset.position = 'right';
        });
    }
    
    // Stil güncellemeleri
    updateStyles(pages);
}

function updateStyles(pages) {
    Array.from(pages).forEach(page => {
        switch(page.dataset.position) {
            case 'left':
                page.style.left = '-35%';
                page.style.right = 'auto';
                page.style.width = '60%';
                page.style.height = '80%';
                page.style.transform = 'translateY(-50%)';
                page.style.zIndex = '1';
                page.style.filter = 'brightness(0.9)';
                break;
            case 'center':
                page.style.left = '50%';
                page.style.right = 'auto';
                page.style.width = '70%';
                page.style.height = '90%';
                page.style.transform = 'translate(-50%, -50%)';
                page.style.zIndex = '2';
                page.style.filter = 'none';
                break;
            case 'right':
                page.style.left = 'auto';
                page.style.right = '-35%';
                page.style.width = '60%';
                page.style.height = '80%';
                page.style.transform = 'translateY(-50%)';
                page.style.zIndex = '1';
                page.style.filter = 'brightness(0.9)';
                break;
        }
    });
}
</script> 