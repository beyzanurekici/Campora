<?php
session_start();
include "../baglanti.php";


if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_giris.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Admin Paneli</title>
<style>
body { font-family: Arial; background:#f0f0f0; margin:0; }
.navbar { background:#333; padding:10px; color:white; display:flex; justify-content:space-between; }
.navbar a { color:white; margin-left:10px; text-decoration:none; }
.navbar a:hover { text-decoration:underline; }
.container { padding:20px; max-width:1200px; margin:auto; }
</style>
</head>
<body>

<div class="navbar">
    <div class="logo"><a href="index.php">Campora</a></div>
    <div class="menu">
        <a href="index.php">Ana Sayfa</a>
 
        <a href="urun.php">Ürün Ekle</a>
        <a href="kategori.php">Kategori Ekle</a>
  <a href="siparisler.php">Siparişler</a>
      
    </div>
    <div class="right-menu">
        Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION["admin_email"]); ?> |
        <a href="index.php?logout=1">Çıkış Yap</a>
    </div>
</div>

<div class="container">
    <h1>Admin Paneli</h1>
    <p>Bu alan sadece adminler için görünür.</p>

    <!-- Ürün listesi include -->
    <?php include "liste.php"; ?>

</div>

</body>
</html>
