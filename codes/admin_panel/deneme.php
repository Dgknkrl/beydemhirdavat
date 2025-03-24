<?php
require_once 'db_connection.php';

if (isset($_GET['resim_id'])) {
    $resim_id = $_GET['resim_id'];
    
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT resim1, resim2, resim3 FROM resimler WHERE resim_id = :resim_id");
    $stmt->execute([':resim_id' => $resim_id]);
    $resimler = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resimler) {
        foreach ($resimler as $key => $blob) {
            if ($blob !== null) {
                $base64Image = base64_encode($blob);
                echo "<img src='data:image/jpeg;base64,{$base64Image}' alt='Resim' style='max-width:200px; margin:10px;'/>";
            }
        }
    } else {
        echo "<p>Bu ID'ye ait resim bulunamadı.</p>";
    }
}
?>
