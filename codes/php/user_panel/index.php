<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beydem Hırdavat - Anasayfa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
        }

        .content {
            margin-top: 100px;
            /* Navbar yüksekliği kadar boşluk */
            padding: 20px;
        }
        .tabs {
            display: flex;
            justify-content: center;
            background: white;
            padding: 10px;
            border-bottom: 2px solid #ddd;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        .tab.active {
            background: #ff7f00;
            color: white;
            border-radius: 5px;
        }
        .content {
            display: none;
            padding: 20px;
            background: white;
            margin: 20px auto;
            width: 60%;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease;
        }
        .content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <?php
    $page = 'anasayfa';    ?>

    <div class="content">
        <!-- Anasayfa içeriği buraya gelecek -->
        <div class="tab-content">
            <div class="tabs">
                <button class="tab active" onclick="openTab(event, 'siparisler')">SİPARİŞLER</button>
                <button class="tab" onclick="openTab(event, 'ulasim')">ULAŞIM</button>
                <button class="tab" onclick="openTab(event, 'iade')">İADE & DEĞİŞİM</button>
                <button class="tab" onclick="openTab(event, 'urunler')">ÜRÜNLER</button>
            </div>

            <div id="siparisler" class="content active">
                <h3>Siparişler Hakkındaki Sıkça Sorulan Sorular</h3>
                <ul>
                    <li>Telefondan sipariş verebilir miyim?</li>
                    <li>Havale ile ödemelerde indirim geçerli mi?</li>
                    <li>Faturamda veya TC Numaramda 11 haneli 1 numara yazıyor. Düzeltilebilir mi?</li>
                    <li>Ürünleriniz orijinal mi?</li>
                    <li>Aldığım ürün yerine başka bir ürün gelir mi?</li>
                </ul>
            </div>
            <div id="ulasim" class="content">
                <h3>Teslimat ve Kargo Bilgileri</h3>
                <ul>
                    <li>Siparişlerim ne zaman kargoya verilir?</li>
                    <li>Hangi kargo firması ile çalışıyorsunuz?</li>
                    <li>Kargo ücretleri nasıl hesaplanıyor?</li>
                    <li>Kargomu nasıl takip edebilirim?</li>
                    <li>Mağazanızdan teslim alma seçeneğiniz var mı?</li>
                </ul>
            </div>
            <div id="iade" class="content">
                <h3>İade ve Değişim Politikaları</h3>
                <ul>
                    <li>Aldığım ürünü kaç gün içinde iade edebilirim?</li>
                    <li>İade işlemi için ne yapmam gerekiyor?</li>
                    <li>Değişim süreci nasıl işliyor?</li>
                    <li>Kargo ücreti iade ediliyor mu?</li>
                    <li>Kusurlu veya yanlış ürün gönderilirse ne yapmalıyım?</li>
                </ul>
            </div>
            <div id="urunler" class="content">
                <h3>Ürünler Hakkında Bilgiler</h3>
                <ul>
                    <li>Ürünlerin garantisi var mı?</li>
                    <li>Stokta olmayan ürünler ne zaman gelir?</li>
                    <li>Ürün açıklamalarında belirtilmeyen özellikleri nasıl öğrenebilirim?</li>
                    <li>Ürünleriniz nerede üretiliyor?</li>
                    <li>Toplu alım yapmak için özel indirim alabilir miyim?</li>
                </ul>
            </div>
        </div>
    </div>


    <?php
    include 'footer.php';
    ?>
    <script>
        function openTab(event, tabId) {
            var contents = document.querySelectorAll('.content');
            var tabs = document.querySelectorAll('.tab');

            contents.forEach(content => content.classList.remove('active'));
            tabs.forEach(tab => tab.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>
</body>

</html>