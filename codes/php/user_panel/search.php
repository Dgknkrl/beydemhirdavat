<?php
require_once '../includes/db_connection.php';

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search_term = mysqli_real_escape_string($conn, $_GET['q']);
    
    // Ürünleri ve özellikleri birleştiren sorgu
    $search_query = "SELECT DISTINCT u.urun_id 
                    FROM urunler u 
                    LEFT JOIN urun_ozellik uo ON u.urun_id = uo.urun_id 
                    WHERE u.urun_id LIKE '%$search_term%' 
                    OR u.urun_adi LIKE '%$search_term%'
                    OR uo.ozellik_adi LIKE '%$search_term%'
                    OR uo.ozellik_deger LIKE '%$search_term%'";
    
    $result = mysqli_query($conn, $search_query);
    
    if (mysqli_num_rows($result) > 0) {
        // Bulunan ürün ID'lerini bir diziye al
        $urun_ids = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $urun_ids[] = $row['urun_id'];
        }
        
        // Ürünler sayfasına yönlendir
        $ids_string = implode(',', $urun_ids);
        header("Location: urunler.php?arama=" . urlencode($search_term));
        exit();
    } else {
        // Ürün bulunamadıysa yine ürünler sayfasına yönlendir
        header("Location: urunler.php?arama=" . urlencode($search_term));
        exit();
    }
}

// Arama terimi yoksa ana sayfaya yönlendir
header("Location: index.php");
exit();
?> 