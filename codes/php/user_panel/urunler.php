<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beydem Hırdavat - Ürünler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
        }
        .content {
            margin-top: 100px;
            padding: 20px;
            min-height: calc(100vh - 100px);
            display: flex;
            gap: 30px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        .filtre-panel {
            width: 300px;
            background: #fff;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: auto;
            position: sticky;
            gap: 10px;
            top: 120px;
            max-height: calc(100vh - 140px);
            overflow-y: auto;
        }
        .filtre-baslik {
            background: #ff6b00;
            color: white;
            padding: 12px 15px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px 8px 0 0;
            margin-bottom: 8px;
        }
        .kategori-grup {
            border-bottom: 1px solid #eee;
            background: #fff;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }
        .kategori-grup:hover {
            background: #f8f8f8;
        }
        .kategori-grup:last-child {
            border-bottom: none;
            border-radius: 0 0 8px 8px;
            margin-bottom: 0;
        }
        .checkbox-grup {
            padding: 15px 20px;
            max-height: none;
        }
        .checkbox-grup label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            color: #444;
            padding: 8px 0;
            transition: all 0.2s ease;
        }
        .checkbox-grup label:hover {
            color: #ff6b00;
        }
        .checkbox-grup input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 2px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .checkbox-grup input[type="checkbox"]:checked {
            border-color: #ff6b00;
            background-color: #ff6b00;
        }
        .urunler-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            min-height: 800px;
        }
        .urunler-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            width: 100%;
            flex: 1;
            align-content: flex-start;
        }
        .urun-kart {
            background: white;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            height: 340px;
            margin-bottom: 20px;
        }
        .urun-kart:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .urun-resim {
            width: 220px;
            height: 220px;
            object-fit: contain;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .urun-kart:hover .urun-resim {
            transform: scale(1.05);
        }
        .urun-baslik {
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            min-height: 40px;
            width: 100%;
        }
        .urun-etiket {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ff6b00;
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: background-color 0.2s;
        }
        .urun-kart:hover .urun-etiket {
            background: #e65100;
        }
        .filtrele-text {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .filtrele-text i {
            font-size: 14px;
        }
        .filtre-buttons {
            display: flex;
            gap: 10px;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 0 0 8px 8px;
            margin-top: auto;
        }
        .ara-button, .sifirla-button {
            padding: 10px 15px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            text-transform: uppercase;
            flex: 1;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .ara-button {
            background: #ff6b00;
            color: white;
        }
        .ara-button:hover {
            background: #e65100;
        }
        .sifirla-button {
            background: #f1f1f1;
            color: #666;
        }
        .sifirla-button:hover {
            background: #e0e0e0;
            color: #333;
        }
        .siralama-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0;
            gap: 20px;
            margin-bottom: 20px;
            width: 100%;
        }
        .arama-kutusu {
            position: relative;
            flex: 2;
            min-width: 200px;
            display: flex;
            align-items: center;
        }
        .arama-kutusu input {
            width: 100%;
            height: 45px;
            padding: 0 45px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #444;
            background-color: #fff;
            transition: all 0.2s ease;
        }
        .arama-kutusu .search-btn {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 10px 15px;
            color: #666;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .arama-kutusu input:focus {
            border-color: #003366;
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
        }
        .arama-kutusu .search-btn:hover {
            color: #003366;
        }
        .arama-kutusu i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 14px;
        }
        .arama-kutusu input:focus,
        .siralama-select:focus {
            outline: none;
            border-color: #003366;
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
        }
        .siralama-select {
            height: 45px;
            padding: 0 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #444;
            background-color: #fff;
            cursor: pointer;
            min-width: 200px;
            max-width: 250px;
            flex: 1;
            box-sizing: border-box;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 35px;
        }
        .urun-bulunamadi {
            width: 100%;
            text-align: center;
            padding: 100px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin: 20px 0;
            grid-column: 1 / -1;
        }
        .urun-bulunamadi i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 20px;
        }
        .urun-bulunamadi h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .urun-bulunamadi p {
            color: #666;
        }
        .accordion-header {
            cursor: pointer;
        }
        .accordion-header .filtre-baslik {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            transition: all 0.3s ease;
            border-radius: 0;
        }
        .accordion-header .filtre-baslik i {
            transition: transform 0.3s ease;
        }
        .accordion-header.active .filtre-baslik i {
            transform: rotate(180deg);
        }
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            opacity: 0;
        }
        .accordion-content.active {
            max-height: none;
            opacity: 1;
        }
        .ozellik-accordion-header {
            cursor: pointer;
            padding: 10px 0;
        }
        .ozellik-baslik {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }
        .ozellik-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            opacity: 0;
        }
        .ozellik-accordion-content.active {
            max-height: none;
            opacity: 1;
        }
        .ozellik-grup {
            border-bottom: 1px solid #eee;
            padding: 0 10px;
        }
        .ozellik-grup:last-child {
            border-bottom: none;
        }
        .filtre-panel::-webkit-scrollbar {
            width: 4px;
        }
        .filtre-panel::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .filtre-panel::-webkit-scrollbar-thumb {
            background: #003366;
            border-radius: 10px;
        }
        .filtre-panel::-webkit-scrollbar-thumb:hover {
            background:rgb(5, 122, 240);
            cursor: pointer;
        }

        /* Büyük Tablet (992px'e kadar) */
        @media (max-width: 992px) {
            .content {
                gap: 20px;
                padding: 15px;
                margin-top: 80px;
            }

            .filtre-panel {
                width: 260px;
            }

            .urunler-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 15px;
            }

            .urun-kart {
                height: 320px;
            }

            .urun-resim {
                width: 200px;
                height: 200px;
            }
        }

        /* Tablet (768px'e kadar) */
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
                gap: 20px;
            }

            .filtre-panel {
                width: 100%;
                position: relative;
                top: 0;
                max-height: none;
                margin-bottom: 20px;
            }

            .siralama-container {
                flex-direction: column;
                gap: 15px;
            }

            .arama-kutusu {
                width: 100%;
            }

            .siralama-select {
                width: 100%;
                max-width: none;
            }

            .urunler-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }

            .urun-kart {
                height: 300px;
            }

            .urun-resim {
                width: 180px;
                height: 180px;
            }

            .urun-baslik {
                font-size: 14px;
            }
        }

        /* Mobil (576px'e kadar) */
        @media (max-width: 576px) {
            .content {
                margin-top: 60px;
                padding: 10px;
            }

            .filtre-baslik {
                padding: 12px 15px;
                font-size: 13px;
            }

            .checkbox-grup {
                padding: 12px 15px;
            }

            .checkbox-grup label {
                font-size: 13px;
            }

            .urunler-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 10px;
            }

            .urun-kart {
                height: 260px;
                padding: 10px;
            }

            .urun-resim {
                width: 140px;
                height: 140px;
                margin-bottom: 15px;
            }

            .urun-baslik {
                font-size: 13px;
                min-height: 35px;
            }

            .urun-etiket {
                padding: 6px;
                font-size: 12px;
            }

            .filtre-buttons {
                padding: 10px;
                gap: 8px;
            }

            .ara-button, .sifirla-button {
                padding: 8px 12px;
                font-size: 12px;
            }

            .ara-button i, .sifirla-button i {
                font-size: 14px;
            }
        }

        /* Küçük Mobil (400px'e kadar) */
        @media (max-width: 400px) {
            .urunler-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 8px;
            }

            .urun-kart {
                height: 240px;
            }

            .urun-resim {
                width: 120px;
                height: 120px;
            }

            .filtre-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .ara-button, .sifirla-button {
                width: 100%;
            }
        }

        /* Filtre paneli için özel scroll */
        @media (max-width: 768px) {
            .filtre-panel {
                overflow-y: hidden;
            }

            .accordion-content.active {
                max-height: 300px;
                overflow-y: auto;
            }

            .accordion-content.active::-webkit-scrollbar {
                width: 4px;
            }

            .accordion-content.active::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .accordion-content.active::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }
        }

        /* Filtre butonu düzenlemeleri */
        .filtre-toggle-btn {
            display: none;
            width: 100%;
            bottom: 20px;
            background: #ff6b00;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            font-size: 14px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .filtre-toggle-btn span {
            margin-right: 8px;
        }

        .filtre-toggle-btn i {
            font-size: 16px;
        }

        /* Mobil görünüm düzenlemeleri */
        @media (max-width: 1000px) {
            .filtre-toggle-btn {
                display: flex;
            }

            .filtre-panel {
                position: fixed;
                left: -100%;
                top: 0;
                width: 300px;
                height: 100vh;
                z-index: 1001;
                transition: all 0.3s ease;
                margin: 0;
                border-radius: 0;

                display: flex;
                flex-direction: column;
            }

            .filtre-panel.active {
                left: 0;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            }

            /* Input ve select yüksekliklerini eşitle */
            .arama-kutusu input,
            .siralama-select,
            .ara-button,
            .sifirla-button {
                height: 42px;
                line-height: 42px;
                box-sizing: border-box;
            }

            .siralama-container {
                gap: 15px;
            }

            .filtre-close-btn {
                display: block;
            }

            .filtre-panel {
                padding-bottom: 0;
            }

            .filtre-buttons {
                position: sticky;
                bottom: 0;
                left: 0;
                width: 100%;
                background: white;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                border-radius: 0;
                margin-top: auto;
                padding: 12px;
                box-sizing: border-box;
            }
        }

        @media (min-width: 1001px) {
            .filtre-close-btn {
                display: none;
            }
        }

        /* Filtre başlığı düzenlemeleri */
        .filtre-baslik {
            background: #ff6b00;
            color: white;
            padding: 12px 15px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px 8px 0 0;
            margin-bottom: 8px;
        }

        .filtrele-text {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .filtrele-text i {
            font-size: 14px;
        }

        /* Kapatma butonu düzenlemeleri */
        .filtre-close-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            transition: all 0.2s ease;
            opacity: 0.8;
        }

        .filtre-close-btn:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Mobil görünüm için düzenlemeler */
        @media (max-width: 1000px) {
            .filtre-panel .filtre-baslik {
                position: relative;
                padding-right: 40px; /* Kapatma butonu için alan */
            }

            .filtre-close-btn {
                display: block;
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
            }
        }

        @media (max-width: 576px) {
            .filtre-panel .filtre-baslik {
                padding: 10px 35px 10px 15px;
            }

            .filtre-close-btn {
                font-size: 16px;
                right: 12px;
            }
        }

        /* Tablet ve Küçük Ekranlar (1024px'e kadar) */
        @media (max-width: 1024px) {
            .content {
                margin-top: 80px;
                padding: 15px;
                gap: 20px;
                flex-direction: column;
            }

            .filtre-panel {
                width: 100%;
                max-width: none;
                position: fixed;
                left: -100%;
                top: 0;
                height: 100vh;
                z-index: 1001;
                margin: 0;
                border-radius: 0;
                transition: all 0.3s ease;
            }

            .filtre-panel.active {
                left: 0;
            }

            .siralama-container {
                flex-wrap: wrap;
                gap: 15px;
            }

            .arama-kutusu {
                flex: 100%;
                order: 1;
            }

            .filtre-toggle-btn {
                order: 2;
                flex: 1;
                min-width: 150px;
            }

            .siralama-select {
                order: 3;
                flex: 1;
                min-width: 150px;
            }

            .urunler-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }

            .urun-kart {
                height: 300px;
                padding: 15px;
            }

            .urun-resim {
                width: 180px;
                height: 180px;
            }
        }

        /* Mobil Ekranlar (768px'e kadar) */
        @media (max-width: 768px) {
            .content {
                margin-top: 65px;
                padding: 10px;
            }

            .siralama-container {
                flex-direction: column;
                gap: 10px;
            }

            .arama-kutusu,
            .filtre-toggle-btn,
            .siralama-select {
                width: 100%;
                min-width: 0;
            }

            .urunler-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 10px;
            }

            .urun-kart {
                height: 260px;
                padding: 10px;
            }

            .urun-resim {
                width: 140px;
                height: 140px;
                margin-bottom: 10px;
            }

            .urun-baslik {
                font-size: 13px;
                min-height: 35px;
            }

            .urun-etiket {
                padding: 6px;
                font-size: 12px;
            }

            .filtre-buttons {
                padding: 10px;
                gap: 8px;
            }

            .ara-button, 
            .sifirla-button {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        /* Küçük Mobil Ekranlar (480px'e kadar) */
        @media (max-width: 480px) {
            .content {
                margin-top: 55px;
                padding: 8px;
            }

            .urunler-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 8px;
            }

            .urun-kart {
                height: 220px;
                padding: 8px;
            }

            .urun-resim {
                width: 120px;
                height: 120px;
                margin-bottom: 8px;
            }

            .urun-baslik {
                font-size: 12px;
                min-height: 32px;
            }

            .urun-etiket {
                padding: 5px;
                font-size: 11px;
            }

            .checkbox-grup label {
                font-size: 13px;
                padding: 6px 0;
            }

            .filtre-baslik {
                padding: 10px;
                font-size: 12px;
            }
        }

        /* Filtre Panel Scroll Düzenlemesi */
        @media (max-height: 600px) {
            .filtre-panel {
                overflow-y: auto;
            }

            .accordion-content.active {
                max-height: 200px;
                overflow-y: auto;
            }
        }

        /* Yatay Ekran Düzenlemesi */
        @media (max-height: 480px) and (orientation: landscape) {
            .content {
                margin-top: 50px;
            }

            .filtre-panel {
                padding-bottom: 60px;
            }

            .filtre-buttons {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background: white;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            }
        }

        /* Yüksek DPI Ekranlar için Optimizasyon */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .urun-resim {
                image-rendering: -webkit-optimize-contrast;
            }
        }

        /* Karanlık Mod Desteği */
        @media (prefers-color-scheme: dark) {
            .filtre-panel,
            .urun-kart {
                background-color: #ffffff;
            }
        }
    </style>
</head>
<body>
    <?php 
    $page = 'urunler';
    include 'navbar.php';
    
    // Veritabanı bağlantısı
    require_once '../includes/db_connection.php';
    
    // Veritabanı bağlantısından hemen sonra
    mysqli_query($conn, "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    
    // Sıralama parametresini al
    $siralama = isset($_GET['siralama']) ? $_GET['siralama'] : 'varsayilan';
    
    // Arama parametresini al
    $arama = isset($_GET['arama']) ? $_GET['arama'] : '';
    
    // Ana kategorileri çek
    $ana_kategoriler_query = "SELECT * FROM ana_kategori";
    $ana_kategoriler_result = mysqli_query($conn, $ana_kategoriler_query);

    // Alt kategorileri çek
    $alt_kategoriler_query = "SELECT * FROM alt_kategori";
    $alt_kategoriler_result = mysqli_query($conn, $alt_kategoriler_query);

    // Seçili kategorileri al
    $secili_ana_kategoriler = isset($_GET['ana_kategori']) ? $_GET['ana_kategori'] : [];
    $secili_alt_kategoriler = isset($_GET['alt_kategori']) ? $_GET['alt_kategori'] : [];
    $secili_markalar = isset($_GET['marka']) ? $_GET['marka'] : [];
    $secili_ozellikler = isset($_GET['ozellik']) ? $_GET['ozellik'] : [];

    // Seçili kategorilere göre özellikleri çek
    $ozellikler_query = "SELECT DISTINCT uo.ozellik_adi, uo.ozellik_deger 
                         FROM urun_ozellik uo
                         JOIN urunler u ON uo.urun_id = u.urun_id
                         WHERE 1=1";

    if (!empty($secili_ana_kategoriler)) {
        $ana_kategori_str = implode(',', array_map('intval', $secili_ana_kategoriler));
        $ozellikler_query .= " AND u.ana_kategori_id IN ($ana_kategori_str)";
    }

    if (!empty($secili_alt_kategoriler)) {
        $alt_kategori_str = implode(',', array_map('intval', $secili_alt_kategoriler));
        $ozellikler_query .= " AND u.alt_kategori_id IN ($alt_kategori_str)";
    }

    $ozellikler_result = mysqli_query($conn, $ozellikler_query);
    
    // Ürünleri çek (filtreleme ve sıralama ile)
    $urunler_query = "SELECT DISTINCT u.*, r.resim1, 
                      GROUP_CONCAT(DISTINCT uo.ozellik_adi, ': ', uo.ozellik_deger) as ozellikler 
                      FROM urunler u 
                      LEFT JOIN resimler r ON u.urun_id = r.urun_id
                      LEFT JOIN urun_ozellik uo ON u.urun_id = uo.urun_id
                      WHERE 1=1";
    
    // Filtreleri uygula
    if (!empty($secili_ana_kategoriler)) {
        $ana_kategori_str = implode(',', array_map('intval', $secili_ana_kategoriler));
        $urunler_query .= " AND u.ana_kategori_id IN ($ana_kategori_str)";
    }
    
    if (!empty($secili_alt_kategoriler)) {
        $alt_kategori_str = implode(',', array_map('intval', $secili_alt_kategoriler));
        $urunler_query .= " AND u.alt_kategori_id IN ($alt_kategori_str)";
    }
    
    if (!empty($secili_markalar)) {
        $marka_str = implode("','", array_map('mysqli_real_escape_string', array($conn), $secili_markalar));
        $urunler_query .= " AND u.marka_adi IN ('$marka_str')";
    }
    
    if (!empty($secili_ozellikler)) {
        foreach ($secili_ozellikler as $ozellik) {
            $ozellik = mysqli_real_escape_string($conn, $ozellik);
            $urunler_query .= " AND EXISTS (
                SELECT 1 FROM urun_ozellik uo2 
                WHERE uo2.urun_id = u.urun_id 
                AND CONCAT(uo2.ozellik_adi, ': ', uo2.ozellik_deger) = '$ozellik'
            )";
        }
    }
    
    // Ürünleri çeken sorguya arama filtresini ekle
    if (!empty($arama)) {
        $arama = mysqli_real_escape_string($conn, $arama);
        $urunler_query .= " AND (u.urun_adi LIKE '%$arama%' OR u.marka_adi LIKE '%$arama%')";
    }
    
    $urunler_query .= " GROUP BY u.urun_id, u.urun_adi, u.marka_adi, u.eklenme_tarihi, u.alt_kategori_id, 
                        u.ana_kategori_id, r.resim1";
    
    // Sıralama
    switch ($siralama) {
        case 'ad_a_z':
            $urunler_query .= " ORDER BY u.urun_adi ASC";
            break;
        case 'ad_z_a':
            $urunler_query .= " ORDER BY u.urun_adi DESC";
            break;
        default:
            $urunler_query .= " ORDER BY u.eklenme_tarihi DESC";
    }
    
    $urunler_result = mysqli_query($conn, $urunler_query);

    // Markaları çek (ana kategorileri çeken kısmın altına ekleyin)
    $markalar_query = "SELECT DISTINCT marka_adi FROM urunler";
    if (!empty($secili_ana_kategoriler)) {
        $markalar_query .= " WHERE ana_kategori_id IN (" . implode(',', array_map('intval', $secili_ana_kategoriler)) . ")";
    }
    $markalar_result = mysqli_query($conn, $markalar_query);
    ?>

    <div class="content">

        <!-- Filtre Panel Overlay -->
        <div class="filtre-panel-overlay"></div>

        <!-- Filtre Panel içine kapatma butonu ekle -->
        <form id="filtre-form" class="filtre-panel">
            <div class="filtre-baslik">
                <div class="filtrele-text">
                    <i class="fas fa-filter"></i>
                    FİLTRELE
                </div>
                <button type="button" class="filtre-close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- 1. Ana Kategoriler -->
            <div class="kategori-grup">
                <div class="accordion-header">
                    <div class="filtre-baslik">
                        ANA KATEGORİLER
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="accordion-content">
                    <div class="checkbox-grup">
                        <?php while($kategori = mysqli_fetch_assoc($ana_kategoriler_result)): ?>
                            <label>
                                <input type="checkbox" name="ana_kategori[]" 
                                       value="<?php echo $kategori['ana_kategori_id']; ?>"
                                       class="ana-kategori-checkbox"
                                       data-kategori-id="<?php echo $kategori['ana_kategori_id']; ?>"
                                       <?php echo in_array($kategori['ana_kategori_id'], $secili_ana_kategoriler) ? 'checked' : ''; ?>>
                                <?php echo $kategori['ana_kategori_adi']; ?>
                            </label>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <!-- 2. Alt Kategoriler -->
            <div class="kategori-grup alt-kategoriler" style="display: none;">
                <div class="accordion-header">
                    <div class="filtre-baslik">
                        ALT KATEGORİLER
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="accordion-content">
                    <div class="checkbox-grup">
                        <?php 
                        mysqli_data_seek($alt_kategoriler_result, 0);
                        while($alt_kategori = mysqli_fetch_assoc($alt_kategoriler_result)): 
                        ?>
                            <label class="alt-kategori-label" data-ana-kategori="<?php echo $alt_kategori['ana_kategori_id']; ?>" style="display: none;">
                                <input type="checkbox" name="alt_kategori[]" 
                                       value="<?php echo $alt_kategori['alt_kategori_id']; ?>"
                                       class="alt-kategori-checkbox"
                                       <?php echo in_array($alt_kategori['alt_kategori_id'], $secili_alt_kategoriler) ? 'checked' : ''; ?>>
                                <?php echo $alt_kategori['alt_kategori_adi']; ?>
                            </label>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <!-- 3. Markalar -->
            <div class="kategori-grup">
                <div class="accordion-header">
                    <div class="filtre-baslik">
                        MARKALAR
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="accordion-content">
                    <div class="checkbox-grup">
                        <?php while($marka = mysqli_fetch_assoc($markalar_result)): ?>
                            <label>
                                <input type="checkbox" name="marka[]" 
                                       value="<?php echo $marka['marka_adi']; ?>"
                                       <?php echo in_array($marka['marka_adi'], $secili_markalar) ? 'checked' : ''; ?>>
                                <?php echo $marka['marka_adi']; ?>
                            </label>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <!-- 4. Özellikler -->
            <div class="kategori-grup" id="ozellikler-grup">
                <div class="accordion-header">
                    <div class="filtre-baslik">
                        ÖZELLİKLER
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="accordion-content">
                    <div class="checkbox-grup">
                        <?php
                        $ozellikler_array = array();
                        while($ozellik = mysqli_fetch_assoc($ozellikler_result)) {
                            if (!isset($ozellikler_array[$ozellik['ozellik_adi']])) {
                                $ozellikler_array[$ozellik['ozellik_adi']] = array();
                            }
                            $ozellikler_array[$ozellik['ozellik_adi']][] = $ozellik['ozellik_deger'];
                        }
                        
                        foreach($ozellikler_array as $ozellik_adi => $degerler): ?>
                            <div class="ozellik-grup">
                                <div class="ozellik-accordion-header">
                                    <div class="ozellik-baslik">
                                        <?php echo $ozellik_adi; ?>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="ozellik-accordion-content">
                                    <?php foreach($degerler as $deger):
                                        $ozellik_value = $ozellik_adi . ': ' . $deger;
                                    ?>
                                        <label>
                                            <input type="checkbox" name="ozellik[]" 
                                                   value="<?php echo $ozellik_value; ?>"
                                                   <?php echo in_array($ozellik_value, $secili_ozellikler) ? 'checked' : ''; ?>>
                                            <?php echo $deger; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Filtre Butonları -->
            <div class="filtre-buttons">
                <button type="submit" class="ara-button">
                    <i class="fas fa-search"></i>
                    Ara
                </button>
                <button type="button" class="sifirla-button" onclick="filtreleriSifirla()">
                    <i class="fas fa-undo"></i>
                    Sıfırla
                </button>
            </div>
        </form>

        <div class="urunler-container">
            <!-- Sıralama seçeneğini buraya taşı -->
            <div class="siralama-container">
                <form action="search.php" method="GET" class="arama-kutusu">
                    <input type="text" name="q" placeholder="Ürün Ara..." 
                           value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <button class="filtre-toggle-btn">
                    <span>Ürünleri Filtrele</span>
                    <i class="fas fa-filter"></i>
                </button>
                <select name="siralama" class="siralama-select">
                    <option value="varsayilan" <?php echo $siralama == 'varsayilan' ? 'selected' : ''; ?>>Varsayılan Sıralama</option>
                    <option value="ad_a_z" <?php echo $siralama == 'ad_a_z' ? 'selected' : ''; ?>>İsim (A-Z)</option>
                    <option value="ad_z_a" <?php echo $siralama == 'ad_z_a' ? 'selected' : ''; ?>>İsim (Z-A)</option>
                </select>
            </div>
            
            <!-- Ürünler Grid -->
            <div class="urunler-grid">
                <?php 
                if (mysqli_num_rows($urunler_result) > 0):
                    while($urun = mysqli_fetch_assoc($urunler_result)): 
                ?>
                    <a href="urun.php?id=<?php echo $urun['urun_id']; ?>" class="urun-kart">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($urun['resim1']); ?>" 
                             alt="<?php echo $urun['urun_adi']; ?>" 
                             class="urun-resim">
                        <div class="urun-etiket"><?php echo $urun['urun_adi']; ?></div>
                    </a>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="urun-bulunamadi">
                        <i class="fas fa-search"></i>
                        <h3>Ürün Bulunamadı</h3>
                        <p>Seçtiğiniz filtrelere uygun ürün bulunmamaktadır.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function guncelleFiltreleri() {
        const seciliAnaKategoriler = Array.from(document.querySelectorAll('.ana-kategori-checkbox:checked'))
            .map(cb => cb.dataset.kategoriId);
        
        // Alt kategorileri güncelle
        const altKategoriLabels = document.querySelectorAll('.alt-kategori-label');
        const altKategorilerDiv = document.querySelector('.alt-kategoriler');
        
        if (seciliAnaKategoriler.length > 0) {
            altKategorilerDiv.style.display = 'block';
            altKategoriLabels.forEach(label => {
                if (seciliAnaKategoriler.includes(label.dataset.anaKategori)) {
                    label.style.display = 'block';
                } else {
                    label.style.display = 'none';
                    const checkbox = label.querySelector('input[type="checkbox"]');
                    if (checkbox) checkbox.checked = false;
                }
            });
            
            // Markaları güncelle - AJAX ile
            fetch(`get_markalar.php?ana_kategoriler=${seciliAnaKategoriler.join(',')}`)
                .then(response => response.json())
                .then(markalar => {
                    const markaGrup = document.querySelector('.marka-grup .checkbox-grup');
                    markaGrup.innerHTML = markalar.map(marka => `
                        <label>
                            <input type="checkbox" name="marka[]" value="${marka}">
                            ${marka}
                        </label>
                    `).join('');
                });
        } else {
            altKategorilerDiv.style.display = 'none';
            altKategoriLabels.forEach(label => {
                label.style.display = 'none';
                const checkbox = label.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            });
        }
    }

    // Ana kategori değişikliklerini dinle
    document.querySelectorAll('.ana-kategori-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', guncelleFiltreleri);
    });

    // Sayfa yüklendiğinde mevcut seçimlere göre filtreleri güncelle
    document.addEventListener('DOMContentLoaded', guncelleFiltreleri);

    function filtreleriSifirla() {
        // Tüm checkboxları temizle
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Sıralamayı varsayılana çevir
        document.querySelector('select[name="siralama"]').value = 'varsayilan';
        
        // Alt kategorileri gizle
        document.querySelector('.alt-kategoriler').style.display = 'none';
        
        // Formu gönder
        document.getElementById('filtre-form').submit();
    }

    // Accordion işlevselliği
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', function() {
            this.classList.toggle('active');
            const content = this.nextElementSibling;
            content.classList.toggle('active');
        });
    });

    // Özellikler için nested accordion
    document.querySelectorAll('.ozellik-accordion-header').forEach(header => {
        header.addEventListener('click', function(e) {
            e.stopPropagation(); // Üst accordion'u etkilememesi için
            this.classList.toggle('active');
            const content = this.nextElementSibling;
            content.classList.toggle('active');
        });
    });

    // Sayfa yüklendiğinde tüm accordion'ları aç
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.classList.add('active');
            header.nextElementSibling.classList.add('active');
        });
    });

    // Sayfa yüklendiğinde URL'deki parametrelere göre filtreleri seç
    document.addEventListener('DOMContentLoaded', function() {
        // URL'den parametreleri al
        const urlParams = new URLSearchParams(window.location.search);
        
        // Ana kategori seçimi
        const anaKategoriParams = urlParams.getAll('ana_kategori[]');
        anaKategoriParams.forEach(kategoriId => {
            const checkbox = document.querySelector(`input[name="ana_kategori[]"][value="${kategoriId}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
        
        // Alt kategori seçimi
        const altKategoriParams = urlParams.getAll('alt_kategori[]');
        altKategoriParams.forEach(kategoriId => {
            const checkbox = document.querySelector(`input[name="alt_kategori[]"][value="${kategoriId}"]`);
            if (checkbox) {
                checkbox.checked = true;
                // Alt kategori bölümünü görünür yap
                document.querySelector('.alt-kategoriler').style.display = 'block';
                // İlgili alt kategori etiketini görünür yap
                const label = checkbox.closest('.alt-kategori-label');
                if (label) {
                    label.style.display = 'block';
                }
            }
        });
        
        // Filtreleri güncelle
        if (anaKategoriParams.length > 0) {
            guncelleFiltreleri();
        }
    });

    // Filtre panel kontrolü için JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const filtreToggleBtn = document.querySelector('.filtre-toggle-btn');
        const filtrePanel = document.querySelector('.filtre-panel');
        const filtrePanelOverlay = document.querySelector('.filtre-panel-overlay');
        const filtreCloseBtn = document.querySelector('.filtre-close-btn');

        function openFilterPanel() {
            filtrePanel.classList.add('active');
            filtrePanelOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeFilterPanel() {
            filtrePanel.classList.remove('active');
            filtrePanelOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (filtreToggleBtn) {
            filtreToggleBtn.addEventListener('click', openFilterPanel);
        }

        if (filtrePanelOverlay) {
            filtrePanelOverlay.addEventListener('click', closeFilterPanel);
        }

        if (filtreCloseBtn) {
            filtreCloseBtn.addEventListener('click', closeFilterPanel);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && filtrePanel.classList.contains('active')) {
                closeFilterPanel();
            }
        });
    });

    // Sıralama select değişikliğini dinle
    document.querySelector('.siralama-select').addEventListener('change', function() {
        const form = document.getElementById('filtre-form');
        const siralamaDegeri = this.value;
        
        // Mevcut sıralama input'u varsa güncelle, yoksa yeni input ekle
        let siralamInput = form.querySelector('input[name="siralama"]');
        if (!siralamInput) {
            siralamInput = document.createElement('input');
            siralamInput.type = 'hidden';
            siralamInput.name = 'siralama';
            form.appendChild(siralamInput);
        }
        siralamInput.value = siralamaDegeri;
        
        // Formu gönder
        form.submit();
    });
    </script>

    <?php 
    mysqli_close($conn);
    include 'footer.php'; 
    ?>
</body>
</html> 