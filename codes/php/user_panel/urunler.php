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
            padding: 15px 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px 8px 0 0;
            margin-bottom: 10px;
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
        .urunler-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
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
        .ara-button, .sifirla-button {
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            text-transform: uppercase;
            flex: 1;
            border: none;
        }
        .ara-button {
            background: #ff6b00;
            color: white;
        }
        .ara-button:hover {
            background: #e65100;
        }
        .sifirla-button {
            background: #666;
            color: white;
        }
        .sifirla-button:hover {
            background: #555;
        }
        .siralama-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #eee;
            border-radius: 6px;
            margin: 15px 20px;
            font-size: 14px;
            color: #444;
            cursor: pointer;
            background-color: #f8f8f8;
            width: calc(100% - 40px);
            transition: all 0.2s ease;
        }
        .siralama-select:hover {
            border-color: #ff6b00;
        }
        .siralama-select:focus {
            outline: none;
            border-color: #ff6b00;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .urunler-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .siralama-container {
            display: flex;
            justify-content: flex-end;
            padding: 0;
        }
        
        .siralama-select {
            width: auto;
            padding: 10px 15px;
            border: 1px solid #eee;
            border-radius: 6px;
            font-size: 14px;
            color: #444;
            cursor: pointer;
            background-color: #fff;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin: 0;
            min-width: 200px;
        }
        
        .siralama-select:hover {
            border-color: #ff6b00;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .siralama-select:focus {
            outline: none;
            border-color: #ff6b00;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Mevcut .urunler-grid stilini güncelle */
        .urunler-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            width: 100%;
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

        /* Scroll çubuğunu özelleştir */
        .filtre-panel::-webkit-scrollbar {
            width: 8px;
        }

        .filtre-panel::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .filtre-panel::-webkit-scrollbar-thumb {
            background: #003366;
            border-radius: 4px;
        }

        .filtre-panel::-webkit-scrollbar-thumb:hover {
            background: #002244;
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
    
    $urunler_query .= " GROUP BY u.urun_id, u.urun_adi, u.marka_adi, u.eklenme_tarihi, u.alt_kategori_id, 
                        u.ana_kategori_id, r.resim1";
    
    // Sıralama
    switch ($siralama) {
        case 'fiyat_artan':
            $urunler_query .= " ORDER BY u.fiyat ASC";
            break;
        case 'fiyat_azalan':
            $urunler_query .= " ORDER BY u.fiyat DESC";
            break;
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
        <form id="filtre-form" class="filtre-panel">
            <div class="filtre-baslik">
                <div class="filtrele-text">
                    <i class="fas fa-filter"></i>
                    FİLTRELE
                </div>
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
                <button type="submit" class="ara-button">ARA</button>
                <button type="button" class="sifirla-button" onclick="filtreleriSifirla()">SIFIRLA</button>
            </div>
        </form>

        <div class="urunler-container">
            <!-- Sıralama seçeneğini buraya taşı -->
            <div class="siralama-container">
                <select name="siralama" class="siralama-select" onchange="document.getElementById('filtre-form').submit()">
                    <option value="varsayilan" <?php echo $siralama == 'varsayilan' ? 'selected' : ''; ?>>Varsayılan Sıralama</option>
                    <option value="fiyat_artan" <?php echo $siralama == 'fiyat_artan' ? 'selected' : ''; ?>>Fiyat (Artan)</option>
                    <option value="fiyat_azalan" <?php echo $siralama == 'fiyat_azalan' ? 'selected' : ''; ?>>Fiyat (Azalan)</option>
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
    </script>

    <?php 
    mysqli_close($conn);
    include 'footer.php'; 
    ?>
</body>
</html> 