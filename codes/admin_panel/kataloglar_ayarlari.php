<?php

require_once 'db_connection.php';



// POST işlemleri için kontrol

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {

        $db = Database::getInstance()->getConnection();



        // Silme işlemi

        if (isset($_POST['islem']) && $_POST['islem'] == 'sil' && isset($_POST['katalog_id'])) {

            $katalog_id = $_POST['katalog_id'];



            $sql = "DELETE FROM kataloglar WHERE katalog_id = ?";

            $stmt = $db->prepare($sql);

            $result = $stmt->execute([$katalog_id]);



            if ($result) {

                echo json_encode(['success' => true, 'message' => 'Katalog başarıyla silindi']);
            } else {

                echo json_encode(['success' => false, 'error' => 'Silme işlemi başarısız']);
            }

            exit;
        }



        // Düzenleme işlemi

        if (isset($_POST['islem']) && $_POST['islem'] == 'duzenle') {

            $katalog_id = $_POST['katalog_id'];

            $isim = $_POST['isim'];

            $kisa_aciklama = $_POST['kisa_aciklama'];

            $tarih = $_POST['tarih'];



            $db->beginTransaction();



            try {

                $sql = "UPDATE kataloglar SET isim = ?, kisa_aciklama = ?, tarih = ? WHERE katalog_id = ?";

                $stmt = $db->prepare($sql);

                $result = $stmt->execute([$isim, $kisa_aciklama, $tarih, $katalog_id]);



                // Resim güncellemeleri

                for ($i = 1; $i <= 3; $i++) {

                    if (isset($_FILES["resim$i"]) && $_FILES["resim$i"]['size'] > 0) {

                        $resimData = file_get_contents($_FILES["resim$i"]['tmp_name']);

                        $sql = "UPDATE kataloglar SET resim$i = ? WHERE katalog_id = ?";

                        $stmt = $db->prepare($sql);

                        $stmt->execute([$resimData, $katalog_id]);
                    }
                }



                $db->commit();

                echo json_encode(['success' => true, 'message' => 'Katalog başarıyla güncellendi']);
            } catch (Exception $e) {

                $db->rollBack();

                throw $e;
            }

            exit;
        }



        // Normal katalog ekleme işlemi

        if (!isset($_POST['islem'])) {

            $isim = $_POST['isim'];

            $kisa_aciklama = $_POST['kisa_aciklama'];

            $tarih = $_POST['tarih'];



            $db->beginTransaction();



            try {

                $sql = "INSERT INTO kataloglar (isim, kisa_aciklama, tarih) VALUES (?, ?, ?)";

                $stmt = $db->prepare($sql);

                $stmt->execute([$isim, $kisa_aciklama, $tarih]);



                $katalog_id = $db->lastInsertId();



                // Resimler için kontrol

                for ($i = 1; $i <= 3; $i++) {

                    if (isset($_FILES["resim$i"]) && $_FILES["resim$i"]['size'] > 0) {

                        $resimData = file_get_contents($_FILES["resim$i"]['tmp_name']);

                        $sql = "UPDATE kataloglar SET resim$i = ? WHERE katalog_id = ?";

                        $stmt = $db->prepare($sql);

                        $stmt->execute([$resimData, $katalog_id]);
                    }
                }



                $db->commit();

                header("Location: kataloglar_ayarlari.php?success=1");
            } catch (Exception $e) {

                $db->rollBack();

                throw $e;
            }

            exit;
        }
    } catch (Exception $e) {

        if (isset($_POST['islem'])) {

            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        } else {

            $error = $e->getMessage();
        }

        exit;
    }
}



// Katalogları çek

try {

    $db = Database::getInstance()->getConnection();

    $sql = "SELECT katalog_id, isim, kisa_aciklama, tarih, resim1, resim2, resim3, olusturma_tarihi 

            FROM kataloglar 

            ORDER BY olusturma_tarihi DESC";

    $stmt = $db->prepare($sql);

    $stmt->execute();

    $kataloglar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {

    $error = $e->getMessage();
}

?>



<!DOCTYPE html>

<html lang="tr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Beydem Hırdavat - Katalog Ekle</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {

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



        .main-content {

            margin-left: 300px;

            width: calc(100% - 300px);

            background: #fff;

            border-radius: 10px;

            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

            padding: 20px;

        }



        .form-group {

            margin-bottom: 20px;

        }



        .form-group label {

            display: block;

            margin-bottom: 8px;

            font-weight: bold;

            color: #333;

        }



        .form-group input[type="text"],

        .form-group input[type="date"],

        .form-group textarea {

            width: 100%;

            padding: 10px;

            border: 1px solid #ddd;

            border-radius: 4px;

            font-size: 14px;

        }



        .form-group textarea {

            height: 100px;

            resize: vertical;

        }



        .image-preview {

            width: 200px;

            height: 200px;

            border: 2px dashed #ddd;

            margin-top: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

            overflow: hidden;

        }



        .image-preview img {

            max-width: 100%;

            max-height: 100%;

            object-fit: contain;

        }



        .image-preview-container {

            display: flex;

            gap: 20px;

            margin-top: 10px;

        }



        .preview-box {

            text-align: center;

        }



        .preview-box label {

            display: block;

            margin-bottom: 10px;

        }



        .submit-btn {

            background-color: #ff6b00;

            color: white;

            padding: 12px 24px;

            border: none;

            border-radius: 4px;

            cursor: pointer;

            font-size: 16px;

            transition: background-color 0.3s;

        }



        .submit-btn:hover {

            background-color: #e65100;

        }



        .alert {

            padding: 15px;

            margin-bottom: 20px;

            border-radius: 4px;

        }



        .alert-success {

            background-color: #d4edda;

            color: #155724;

            border: 1px solid #c3e6cb;

        }



        .alert-danger {

            background-color: #f8d7da;

            color: #721c24;

            border: 1px solid #f5c6cb;

        }



        .form-container {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 70px;

        }

        .form-right-column {

            background-color: #f8f9fa;



            border-radius: 8px;

        }



        .image-upload-container {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 15px;

            margin-top: 10px;

        }



        .image-preview {

            width: 100%;

            aspect-ratio: 1;

            height: auto;

            border: 2px dashed #ddd;

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

            overflow: hidden;

            background-color: white;

            cursor: pointer;

            transition: all 0.3s ease;

            border-radius: 6px;

        }



        .image-preview img {

            width: 100%;

            height: 100%;

            object-fit: cover;

        }



        .image-preview:hover {

            border-color: #ff6b00;

            background-color: #fff8f3;

        }



        .preview-box {

            width: 100%;

        }



        .preview-box label {

            color: #666;

            font-size: 13px;

            margin-bottom: 5px;

            display: block;

        }



        .form-actions {

            grid-column: 1 / -1;

            text-align: right;

            margin-top: 20px;

        }



        h2 {

            margin-bottom: 30px;

            color: #333;

            font-size: 24px;

        }



        .section-title {

            font-size: 18px;

            color: #666;

            margin-bottom: 20px;

            padding-bottom: 10px;

            border-bottom: 2px solid #eee;

        }



        .time-input {

            position: relative;

        }



        .time-input input {

            padding-right: 30px;

        }



        .time-input::after {

            content: '\f133';
            /* Takvim ikonu */

            font-family: 'Font Awesome 5 Free';

            font-weight: 900;

            position: absolute;

            right: 10px;

            top: 50%;

            transform: translateY(-50%);

            color: #666;

            pointer-events: none;

        }



        /* Katalog listesi için stiller */

        .katalog-list {

            margin-top: 40px;

            border-top: 2px solid #eee;

            padding-top: 20px;

        }



        .katalog-list h3 {

            color: #333;

            margin-bottom: 20px;

        }



        .katalog-table {

            width: 100%;

            border-collapse: collapse;

            background: white;

            border-radius: 8px;

            overflow: hidden;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

        }



        .katalog-table th,

        .katalog-table td {

            padding: 12px 15px;

            text-align: left;

            border-bottom: 1px solid #eee;

        }



        .katalog-table th {

            background-color: #f8f9fa;

            font-weight: 600;

            color: #333;

        }



        .katalog-table tr:hover {

            background-color: #f8f9fa;

        }



        .katalog-table .resim-preview {

            width: 60px;

            height: 60px;

            object-fit: cover;

            border-radius: 4px;

        }



        .katalog-table .actions {

            display: flex;

            gap: 10px;

        }



        .action-btn {

            background: none;

            border: none;

            padding: 5px;

            cursor: pointer;

            color: #666;

            transition: color 0.3s;

        }



        .action-btn:hover {

            color: #ff6b00;

        }



        .action-btn.delete:hover {

            color: #dc3545;

        }



        .add-button {

            background-color: #ff6b00;

            color: white;

            padding: 12px 24px;

            border: none;

            border-radius: 4px;

            cursor: pointer;

            font-size: 16px;

            margin-bottom: 20px;

            display: flex;

            align-items: center;

            gap: 8px;

        }



        .add-button:hover {

            background-color: #e65100;

        }



        .add-form {

            display: none;

            margin-bottom: 30px;

            padding: 20px;

            background: #f8f9fa;

            border-radius: 8px;

            border: 1px solid #eee;

        }



        .add-form.active {

            display: block;

        }



        .edit-form {

            display: none;

            margin-bottom: 30px;

            padding: 20px;

            background: #f8f9fa;

            border-radius: 8px;

            border: 1px solid #eee;

        }



        .edit-form.active {

            display: block;

        }



        .close-btn {

            float: right;

            background: none;

            border: none;

            font-size: 20px;

            color: #666;

            cursor: pointer;

        }



        .close-btn:hover {

            color: #333;

        }
    </style>

</head>

<body>

    <div class="container">

        <?php include 'includes/sidebar.php'; ?>



        <div class="main-content">

            <h2>Katalog Yönetimi</h2>



            <?php if (isset($_GET['success'])): ?>

                <div class="alert alert-success">

                    <?php if ($_GET['success'] == 1): ?>

                        Katalog başarıyla eklendi!

                    <?php elseif ($_GET['success'] == 2): ?>

                        Katalog başarıyla güncellendi!

                    <?php elseif ($_GET['success'] == 3): ?>

                        Katalog başarıyla silindi!

                    <?php endif; ?>

                </div>

            <?php endif; ?>



            <?php if (isset($error)): ?>

                <div class="alert alert-danger">

                    <?php echo $error; ?>

                </div>

            <?php endif; ?>



            <!-- Katalog Listesi -->

            <div class="katalog-list">

                <h3>Mevcut Kataloglar</h3>



                <!-- Katalog Tablosu -->

                <table class="katalog-table">

                    <thead>

                        <tr>

                            <th>Resim</th>

                            <th>Katalog İsmi</th>

                            <th>Dönem</th>

                            <th>Açıklama</th>

                            <th>Kullanılabilir Zaman Aralığı</th>

                            <th>İşlemler</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($kataloglar)): ?>

                            <?php foreach ($kataloglar as $katalog): ?>

                                <tr data-id="<?= $katalog['katalog_id'] ?>"
                                    data-resim1="<?= base64_encode($katalog['resim1']) ?>"
                                    data-resim2="<?= base64_encode($katalog['resim2']) ?>"
                                    data-resim3="<?= base64_encode($katalog['resim3']) ?>">

                                    <td>

                                        <?php if ($katalog['resim1']): ?>

                                            <img src="data:image/jpeg;base64,<?= base64_encode($katalog['resim1']) ?>"
                                                alt="<?= htmlspecialchars($katalog['isim']) ?>" class="resim-preview">

                                        <?php else: ?>

                                            <div class="resim-preview"
                                                style="background: #eee; display: flex; align-items: center; justify-content: center;">

                                                <i class="fas fa-image" style="color: #999;"></i>

                                            </div>

                                        <?php endif; ?>

                                    </td>

                                    <td><?= htmlspecialchars($katalog['isim']) ?></td>

                                    <td><?= htmlspecialchars($katalog['tarih']) ?></td>

                                    <td><?= htmlspecialchars($katalog['kisa_aciklama']) ?></td>

                                    <td><?= htmlspecialchars($katalog['tarih']) ?></td>

                                    <td class="actions">

                                        <button type="button" class="action-btn edit"
                                            onclick="katalogDuzenle(<?= $katalog['katalog_id'] ?>)">

                                            <i class="fas fa-edit"></i>

                                        </button>

                                        <button type="button" class="action-btn delete"
                                            onclick="katalogSil(<?= $katalog['katalog_id'] ?>)">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="6" style="text-align: center;">Henüz katalog eklenmemiş.</td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>



                <!-- Katalog Ekleme Butonu -->

                <div style="margin-top: 30px;">

                    <button class="add-button" onclick="toggleAddForm()">

                        <i class="fas fa-plus"></i>

                        Yeni Katalog Ekle

                    </button>

                </div>



                <!-- Katalog Ekleme Formu -->

                <div class="add-form" id="addForm">

                    <button class="close-btn" onclick="toggleAddForm()">×</button>

                    <form action="" method="POST" enctype="multipart/form-data">

                        <div class="form-container">

                            <!-- Sol Sütun - Temel Bilgiler -->

                            <div class="form-left-column">

                                <div class="section-title">Temel Bilgiler</div>

                                <div class="form-group">

                                    <label for="isim">Katalog İsmi</label>

                                    <input type="text" id="isim" name="isim" required>

                                </div>



                                <div class="form-group">

                                    <label for="kisa_aciklama">Kısa Açıklama</label>

                                    <textarea id="kisa_aciklama" name="kisa_aciklama" required></textarea>

                                </div>



                                <div class="form-group">

                                    <label for="tarih">Dönem</label>

                                    <div class="time-input">

                                        <input type="text" id="tarih" name="tarih" required
                                            placeholder="ör: Haziran - Temmuz 2020"
                                            pattern="^[A-Za-zğüşıöçĞÜŞİÖÇ]+ - [A-Za-zğüşıöçĞÜŞİÖÇ]+ [0-9]{4}$"
                                            title="Lütfen geçerli bir dönem giriniz (ör: Haziran - Temmuz 2020)">

                                    </div>

                                </div>

                            </div>



                            <!-- Sağ Sütun - Resim Yükleme -->

                            <div class="form-right-column">

                                <div class="section-title">Katalog Resimleri</div>

                                <div class="image-upload-container">

                                    <?php for ($i = 1; $i <= 3; $i++): ?>

                                        <div class="preview-box">

                                            <label>Resim <?php echo $i; ?></label>

                                            <div class="image-preview" id="preview<?php echo $i; ?>"
                                                title="Resim seçmek için tıklayın">

                                                <i class="fas fa-plus"></i>

                                            </div>

                                            <input type="file" id="resim<?php echo $i; ?>" name="resim<?php echo $i; ?>"
                                                accept="image/*" style="display: none;"
                                                onchange="previewImage(this, <?php echo $i; ?>)">

                                        </div>

                                    <?php endfor; ?>

                                </div>

                            </div>



                            <!-- Form Actions -->

                            <div class="form-actions">

                                <button type="submit" class="submit-btn">

                                    <i class="fas fa-save"></i>

                                    Katalog Ekle

                                </button>

                            </div>

                        </div>

                    </form>

                </div>



                <!-- Katalog Düzenleme Formu -->

                <div class="edit-form" id="editForm">

                    <button class="close-btn" onclick="toggleEditForm()">×</button>

                    <h3>Katalog Düzenle</h3>

                    <form action="" method="POST" enctype="multipart/form-data" id="editFormContent">

                        <!-- Form içeriği JavaScript ile doldurulacak -->

                    </form>

                </div>

            </div>

        </div>

    </div>



    <script>
        // Form değişiklik kontrolü için global değişken

        let formChanged = false;



        // Formları kontrol eden fonksiyonlar

        function toggleAddForm() {

            const addForm = document.getElementById('addForm');

            const editForm = document.getElementById('editForm');



            if (editForm && editForm.style.display === 'block') {

                if (formChanged) {

                    if (!confirm('Kaydedilmemiş değişiklikler var. Devam etmek istiyor musunuz?')) {

                        return;

                    }

                }

                editForm.style.display = 'none';

                if (document.getElementById('editFormContent')) {

                    document.getElementById('editFormContent').innerHTML = '';

                }

                formChanged = false; // Form kapatıldığında değişiklik bayrağını sıfırla

                addForm.classList.toggle('active');

            } else {

                addForm.classList.toggle('active');

            }

        }



        // Resim önizleme için tıklama olayları

        document.querySelectorAll('.image-preview').forEach((preview, index) => {

            preview.addEventListener('click', () => {

                document.getElementById(`resim${index + 1}`).click();

            });

        });



        // Resim önizleme fonksiyonu

        function previewImage(input, index) {

            const preview = document.getElementById(`preview${index}`);

            if (!preview) return;



            if (input.files && input.files[0]) {

                const reader = new FileReader();



                reader.onload = function (e) {

                    preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;

                }



                reader.readAsDataURL(input.files[0]);

            }

        }



        // Tarih input doğrulama

        const tarihInput = document.getElementById('tarih');

        if (tarihInput) {

            tarihInput.addEventListener('input', function (e) {

                let value = e.target.value;



                value = value.replace(/i̇/g, 'İ').replace(/i/g, 'İ');

                value = value.toLocaleUpperCase('tr-TR');



                value = value.split(' ').map(word => {

                    if (word === '-') return word;

                    return word.charAt(0).toLocaleUpperCase('tr-TR') +

                        word.slice(1).toLocaleLowerCase('tr-TR');

                }).join(' ');



                e.target.value = value;

            });

        }



        // Katalog düzenleme

        function katalogDuzenle(id) {

            if (!id) return alert('Katalog ID bulunamadı');



            // Eğer mevcut formda değişiklik varsa uyar

            if (formChanged) {

                if (!confirm('Kaydedilmemiş değişiklikler var. Devam etmek istiyor musunuz?')) {

                    return;

                }

            }



            const editForm = document.getElementById('editForm');

            const addForm = document.getElementById('addForm');



            // Eğer ekleme formu açıksa kapat

            if (addForm.classList.contains('active')) {

                if (confirm('Yeni katalog ekleme işlemi yarıda kalacak. Devam etmek istiyor musunuz?')) {

                    addForm.classList.remove('active');

                    addForm.querySelector('form').reset();

                } else {

                    return;

                }

            }



            if (editForm) {

                editForm.style.display = 'block';

                formChanged = false;



                const row = document.querySelector(`tr[data-id="${id}"]`);

                if (row) {

                    const formContent = document.getElementById('editFormContent');



                    // Mevcut resimleri al

                    const resimler = [];

                    for (let i = 1; i <= 3; i++) {

                        const resimData = row.getAttribute(`data-resim${i}`);

                        resimler.push(resimData ? `data:image/jpeg;base64,${resimData}` : null);

                    }



                    // Form içeriğini oluştur

                    formContent.innerHTML = `

                        <input type="hidden" name="islem" value="duzenle">

                        <input type="hidden" name="katalog_id" value="${id}">

                        <div class="form-container">

                            <div class="form-left-column">

                                <div class="section-title">Temel Bilgiler</div>

                                <div class="form-group">

                                    <label for="edit_isim">Katalog İsmi</label>

                                    <input type="text" id="edit_isim" name="isim" value="${row.children[1].textContent.trim()}" required>

                                </div>

                                <div class="form-group">

                                    <label for="edit_kisa_aciklama">Kısa Açıklama</label>

                                    <textarea id="edit_kisa_aciklama" name="kisa_aciklama" required>${row.children[3].textContent.trim()}</textarea>

                                </div>

                                <div class="form-group">

                                    <label for="edit_tarih">Dönem</label>

                                    <div class="time-input">

                                        <input type="text" id="edit_tarih" name="tarih" value="${row.children[2].textContent.trim()}" required>

                                    </div>

                                </div>

                            </div>

                            <div class="form-right-column">

                                <div class="section-title">Katalog Resimleri</div>

                                <div class="image-upload-container">

                                    ${[1, 2, 3].map(i => `

                                        <div class="preview-box">

                                            <label>Resim ${i}</label>

                                            <div class="image-preview" id="edit_preview${i}" onclick="document.getElementById('edit_resim${i}').click()">

                                                ${resimler[i - 1] ?

                            `<img src="${resimler[i - 1]}" style="width:100%;height:100%;object-fit:cover;">` :

                            '<i class="fas fa-plus"></i>'}

                                            </div>

                                            <input type="file" 

                                                   id="edit_resim${i}" 

                                                   name="resim${i}" 

                                                   accept="image/*" 

                                                   style="display:none" 

                                                   onchange="previewImage(this, 'edit_preview${i}')">

                                        </div>

                                    `).join('')}

                                </div>

                            </div>

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="submit-btn">

                                <i class="fas fa-save"></i>

                                Değişiklikleri Kaydet

                            </button>

                        </div>

                    `;



                    // Form içeriğine değişiklik takibi ekle

                    formContent.addEventListener('input', function () {

                        formChanged = true;

                    });



                    // Form submit olayını dinle

                    formContent.addEventListener('submit', function (e) {

                        e.preventDefault();

                        const formData = new FormData(this);



                        fetch('kataloglar_ayarlari.php', {

                            method: 'POST',

                            body: formData

                        })

                            .then(response => response.json())

                            .then(data => {

                                if (data.success) {

                                    formChanged = false;

                                    alert('Katalog başarıyla güncellendi');

                                    location.reload();

                                } else {

                                    alert(data.error || 'Güncelleme sırasında bir hata oluştu');

                                }

                            })

                            .catch(error => {

                                console.error('Hata:', error);

                                alert('Bir hata oluştu');

                            });

                    });

                }

            }

        }



        // Katalog silme

        function katalogSil(id) {

            if (!id) return alert('Katalog ID bulunamadı');



            if (confirm('Bu kataloğu silmek istediğinize emin misiniz?')) {

                const formData = new FormData();

                formData.append('islem', 'sil');

                formData.append('katalog_id', id);



                fetch('kataloglar_ayarlari.php', { // URL'yi düzelttik

                    method: 'POST',

                    body: formData

                })

                    .then(response => response.json())

                    .then(data => {

                        if (data.success) {

                            alert('Katalog başarıyla silindi');

                            location.reload();

                        } else {

                            alert(data.error || 'Silme işlemi sırasında bir hata oluştu');

                        }

                    })

                    .catch(error => {

                        console.error('Hata:', error);

                        alert('Bir hata oluştu');

                    });

            }

        }



        // Yeni katalog ekleme formunun submit olayını dinle

        document.querySelector('#addForm form').addEventListener('submit', function (e) {

            e.preventDefault();

            const formData = new FormData(this);



            fetch('kataloglar_ayarlari.php', { // URL'yi düzelttik

                method: 'POST',

                body: formData

            })

                .then(response => {

                    if (response.redirected) {

                        window.location.href = response.url;

                    } else {

                        return response.json();

                    }

                })

                .then(data => {
                    if (data && !data.success) {
                        alert(data.error || 'Ekleme sırasında bir hata oluştu');
                    }
                })
                .catch(error => {

                    console.error('Hata:', error);

                    alert('Bir hata oluştu');
                });
        });
        // Sayfa yenileme veya kapatma öncesi kontrol
        window.addEventListener('beforeunload', function (e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'Kaydedilmemiş değişiklikler var. Sayfadan ayrılmak istediğinize emin misiniz?';
            }
        });
    </script>
</body>
</html>