<?php
session_start();
include "baglanti.php";


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$girisYapti = isset($_SESSION['musteri_id']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campora</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background: #333; padding: 10px 20px; color: white; }
        .navbar a { color: white; text-decoration: none; margin: 0 10px; }
        .navbar a:hover { text-decoration: underline; }
        .menu { list-style: none; display: flex; margin: 0; padding: 0; }
        .menu li { margin: 0 10px; position: relative; }
        .right-menu { display: flex; align-items: center; }
        .btn { padding: 6px 12px; background: #555; border-radius: 5px; margin-left: 10px; color:white; text-decoration:none; }
        .btn:hover { background: #777; }
        main { padding: 20px; }

        
        .menu, .menu ul { list-style: none; padding: 0; margin: 0; }
        .menu > li { display: inline-block; }
        .menu a { text-decoration: none; padding: 8px 12px; display: block; color: #fff; background-color: #333; }
        .menu a:hover { background-color: #555; }
        .submenu { display: none; position: absolute; top: 100%; left: 0; background: #444; border: 1px solid #222; min-width: 150px; z-index: 1000; }
        .submenu li { display: block; }
        .submenu a { background-color: #444; color: #fff; }
        .submenu a:hover { background-color: #666; }
        .menu li:hover .submenu { display: block; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo"><a href="index.php" style="color:white; font-weight:bold;">Campora</a></div>

    <ul class="menu">
        <li><a href="index.php">Ana Sayfa</a></li>
        <li>
            <a href="#">Kategoriler</a>
            <ul class="submenu">
                <li><a href="listele.php?kategori_id=1">Çadır</a></li>
                <li><a href="listele.php?kategori_id=2">Uyku Ekipmanı</a></li>
                <li><a href="listele.php?kategori_id=3">Kamp Mobilyaları</a></li>
                <li><a href="listele.php?kategori_id=4">Giyim</a></li>
                <li><a href="listele.php?kategori_id=5">Çanta ve Taşıma</a></li>
            </ul>
        </li>
        <li><a href="hakkimizda.php">Hakkımızda</a></li>
        <li><a href="iletisim.php">İletişim</a></li>
    </ul>

    <div class="right-menu">
        <?php if ($girisYapti): ?>
            <a href="index.php?logout=1" class="btn">Çıkış Yap</a>
        <?php else: ?>
            <a href="giris.php" class="btn">Giriş Yap</a>
        <?php endif; ?>
        <a href="sepet.php" class="btn">Sepet</a>
    </div>
</nav>

<!-- Menü altına ürün listesini include ediyoruz -->
<main>
    <?php include "listele.php"; ?>
</main>

</body>
</html>
