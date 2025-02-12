<?php
require_once 'db_connection.php';

// Veritabanı bağlantısını al
$conn = Database::getInstance()->getConnection();

$sql = "SELECT * FROM iletisim_formu ORDER BY gonderim_tarihi DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beydem Hırdavat - İletişim Ayarları</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            display: flex;
            min-height: calc(100vh - 40px);
            gap: 20px;
            position: relative;
        }

        .sidebar {
            width: 280px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px 0;
            position: fixed;
            top: 20px;
            bottom: 20px;
            overflow-y: auto;
        }

        .panel-title {
            font-size: 18px;
            color: #333;
            padding: 0 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .menu-list {
            list-style: none;
            padding: 15px;
            margin: 0;
        }

        .menu-item {
            padding: 12px 20px;
            color: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .menu-item:hover {
            background-color: #f0f0f0;
            color: #333;
        }

        .menu-item.active {
            background-color: #ff6b00;
            color: white !important;
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
        }

        .main-content {
            margin-left: 300px;
            width: calc(100% - 300px);
            flex-grow: 1;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #ff6b00;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .delete-btn {
            color: #003366;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2 class="panel-title">PANEL AYARLARI</h2>
            <ul class="menu-list">
                <li class="menu-group">
                    <a href="urun_ayarlari.php" class="menu-item active">
                        <i class="fas fa-box"></i>
                        Ürün Ayarları
                    </a>
                    <ul class="submenu">
                        <li><a href="urun_ayarlari.php" class="menu-item">Ürünleri Görüntüle</a></li>
                        <li><a href="urun_ekle.php" class="menu-item">Ürün Ekle</a></li>
                    </ul>
                </li>
                <a href="kategori_ayarlari.php" class="menu-item">
                    <i class="fas fa-tags"></i>
                    Kategori Ayarları
                </a>
                <a href="iletisim_kayitlari.php" class="menu-item">
                    <i class="fas fa-address-book"></i>
                    İletişim Kayıtları
                </a>
            </ul>
        </div>

        <div class="main-content">
        <h2>İLETİŞİM KAYITLARI</h2>
        <table id="contactTable">
            <tr>
                <th onclick="sortTable(0)" style="cursor: pointer;">Ad </th>
                <th onclick="sortTable(1)" style="cursor: pointer;">Soyad </th>
                <th onclick="sortTable(2)" style="cursor: pointer;">E-posta </th>
                <th onclick="sortTable(3)" style="cursor: pointer;">Konu </th>
                <th onclick="sortTable(4)" style="cursor: pointer;">Mesaj </th>
                <th onclick="sortTable(5)" style="cursor: pointer;">Gönderim Tarihi </th>
                <th>Sil</th>
            </tr>
            <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['ad']) ?></td>
                <td><?= htmlspecialchars($row['soyad']) ?></td>
                <td><?= htmlspecialchars($row['eposta']) ?></td>
                <td><?= htmlspecialchars($row['konu']) ?></td>
                <td><?= htmlspecialchars($row['mesaj']) ?></td>
                <td><?= htmlspecialchars($row['gonderim_tarihi']) ?></td>
                <td>
                    <a href="delete_record.php?id=<?= $row['iletisim_id'] ?>" class="delete-btn" onclick="return false;">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    </div>


    <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("contactTable");
            switching = true;
            dir = "asc"; 
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }

        // Silme işlemi için event listener ekle
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (confirm('Bu kaydı silmek istediğinize emin misiniz?')) {
                    const deleteUrl = this.getAttribute('href');
                    
                    fetch(deleteUrl)
                        .then(response => {
                            if (response.ok) {
                                // Silme başarılı olduğunda satırı tablodan kaldır
                                this.closest('tr').remove();
                            } else {
                                throw new Error('Silme işlemi başarısız oldu');
                            }
                        })
                        .catch(error => {
                            alert('Hata: ' + error.message);
                        });
                }
            });
        });
    </script>
</body>
</html>
