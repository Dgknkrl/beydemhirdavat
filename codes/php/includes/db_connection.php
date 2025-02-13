<?php
$servername = "localhost";
$username = "root";  // Veritabanı kullanıcı adınız
$password = "";      // Veritabanı şifreniz
$dbname = "beydemhirdavat";  // Veritabanı adınız

// Veritabanı bağlantısını oluştur
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if (!$conn) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

// Türkçe karakter sorunlarını önlemek için
mysqli_set_charset($conn, "utf8");
?> 