<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beydem Hırdavat - Kataloglar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
        }
        .content {
            margin-top: 100px;
            padding: 20px;
            min-height: calc(100vh - 100px);
        }
        .katalog-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .katalog-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
        }
        .katalog-card img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .katalog-card h3 {
            color: #003366;
            margin: 15px 0;
        }
        .download-btn {
            background: #003366;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .download-btn:hover {
            background: #004080;
        }
    </style>
</head>
<body>
    <?php 
    $page = 'kataloglar';
    include 'navbar.php'; 
    ?>

    <div class="content">
        <div class="katalog-container">
            <!-- Katalog kartları buraya gelecek -->
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html> 