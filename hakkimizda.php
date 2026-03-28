
<?php
session_start();
$girisYapti = isset($_SESSION["musteri_id"]);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hakkımızda - Campora</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f4f4; }

        /* Navbar */
        .navbar {
            background:#333;
            color:white;
            padding:12px 20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        .navbar a {
            color:white;
            text-decoration:none;
            margin:0 10px;
        }
        .navbar a:hover { text-decoration:underline; }
        .menu { list-style:none; margin:0; padding:0; display:flex; }
        .menu li { margin-left:20px; }

        /* Sayfa İçeriği */
        .container {
            max-width:900px;
            margin:40px auto;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 15px rgba(0,0,0,0.1);
        }
        h1 { text-align:center; margin-bottom:20px; }
        p { line-height:1.7; margin-bottom:15px; font-size:16px; color:#444; }

        /* Footer */
        .footer {
            text-align:center;
            padding:20px;
            background:#333;
            color:white;
            margin-top:40px;
        }
    </style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo"><a href="index.php" style="font-weight:bold;">Campora</a></div>

    <ul class="menu">
        <li><a href="index.php">Ana Sayfa</a></li>
        <li><a href="hakkimizda.php">Hakkımızda</a></li>
        <li><a href="iletisim.php">İletişim</a></li>
        <li><a href="sepet.php">Sepet</a></li>
        <?php if(!$girisYapti): ?>
            <li><a href="giris.php">Giriş Yap</a></li>
        <?php else: ?>
            <li><a href="index.php?logout=1">Çıkış Yap</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="container">
    <h1>Hakkımızda</h1>

    <p>
         Campora olarak kamp, doğa sporları ve outdoor ekipman alanında en kaliteli ürünleri
        en uygun fiyatlarla müşterilerimize sunmayı amaçlayan bir e-ticaret platformuyuz.
    </p>

    <p>
        Sitemizde çadırlardan kamp mobilyalarına, giyim ürünlerinden taşıma ekipmanlarına kadar
        yüzlerce ürün çeşidi bulunmaktadır. Her ürün, uzman ekibimiz tarafından seçilerek satışa sunulur.
    </p>

    <p>
        Misyonumuz; doğayı seven, kamp yapmayı ve yeni yerler keşfetmeyi seven herkesin
        ihtiyaçlarını güvenilir ve hızlı bir şekilde karşılamaktır.
    </p>

    <p>
        Güvenli ödeme yöntemleri, hızlı kargo ve müşteri memnuniyeti odaklı yaklaşımımız ile
        alışveriş deneyiminizi en üst seviyeye çıkarmak için çalışıyoruz.
    </p>

    <p>
        Campora olarak “Doğaya açılan kapı” sloganıyla, maceranızda yanınızda olmaktan mutluluk duyuyoruz.
    </p>
</div>

<div class="footer">
    © <?php echo date("Y"); ?> Campora — Tüm Hakları Saklıdır.
</div>

</body>
</html>

