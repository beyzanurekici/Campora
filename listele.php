<?php

include "baglanti.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['urun_id'])) {
    $urun_id = (int)$_POST['urun_id'];
    $adet = isset($_POST['adet']) ? (int)$_POST['adet'] : 1;

    
    if (!isset($_SESSION['sepet'])) {
        $_SESSION['sepet'] = [];
    }

    
    if (isset($_SESSION['sepet'][$urun_id])) {
        $_SESSION['sepet'][$urun_id] += $adet;
    } else {
        $_SESSION['sepet'][$urun_id] = $adet;
    }

    $mesaj = "Ürün sepete eklendi!";
}


$kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;


if($kategori_id > 0){
    $stmt = mysqli_prepare($baglanti, "
        SELECT u.id, k.kategori, u.urun_adi, u.aciklama, u.fiyat, u.stok, u.resim
        FROM urunler u
        JOIN kategori k ON u.kategori_id = k.id
        WHERE u.kategori_id = ?
        ORDER BY u.id DESC
    ");
    mysqli_stmt_bind_param($stmt, "i", $kategori_id);
    mysqli_stmt_execute($stmt);
    $urunler = mysqli_stmt_get_result($stmt);
} else {
    $urunler = mysqli_query($baglanti, "
        SELECT u.id, k.kategori, u.urun_adi, u.aciklama, u.fiyat, u.stok, u.resim
        FROM urunler u
        JOIN kategori k ON u.kategori_id = k.id
        ORDER BY u.id DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ürünler</title>
<style>
body { font-family: Arial; background:#f9f9f9; margin:0; padding:20px; }
.container { max-width:1200px; margin:auto; padding:20px; }
h1 { text-align:center; margin-bottom:20px; }
.mesaj { background:#ddffdd; padding:10px; border:1px solid #7aff7a; border-radius:5px; margin-bottom:10px; text-align:center; }

.urun-list { display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:20px; }
.urun { background:white; padding:15px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); text-align:center; }
.urun img { max-width:100%; height:200px; object-fit:cover; border-radius:5px; }
.urun h3 { margin:10px 0 5px; font-size:18px; }
.urun p { font-size:14px; color:#555; height:60px; overflow:hidden; }
.urun .fiyat { font-weight:bold; margin-top:5px; color:#333; }
.urun .stok { margin-top:5px; }
.urun .kategori { margin-top:5px; font-size:13px; color:#777; }
.urun form { margin-top:10px; }
.urun input[type="number"] { width:50px; padding:5px; }
.urun button { padding:6px 12px; margin-top:5px; background:#333; color:white; border:none; border-radius:5px; cursor:pointer; }
.urun button:hover { background:#555; }
</style>
</head>
<body>

<div class="container">
    <h1>Ürünler <?php if($kategori_id) echo "(Kategori: $kategori_id)"; ?></h1>

    <?php if(isset($mesaj)) echo "<div class='mesaj'>$mesaj</div>"; ?>

    <div class="urun-list">
        <?php if(mysqli_num_rows($urunler) > 0): ?>
            <?php while($u = mysqli_fetch_assoc($urunler)): ?>
                <div class="urun">
                    <img src="<?php echo htmlspecialchars($u['resim']); ?>" alt="<?php echo htmlspecialchars($u['urun_adi']); ?>">
                    <h3><?php echo htmlspecialchars($u['urun_adi']); ?></h3>
                    <p><?php echo htmlspecialchars($u['aciklama']); ?></p>
                    <div class="fiyat"><?php echo number_format($u['fiyat'],2,',','.') ?> ₺</div>
                    <div class="stok">Stok: <?php echo $u['stok']; ?></div>
                    <div class="kategori">Kategori: <?php echo htmlspecialchars($u['kategori']); ?></div>

                    <!-- Sepete Ekle Formu -->
                    <form method="POST">
                        <input type="hidden" name="urun_id" value="<?php echo $u['id']; ?>">
                        <input type="number" name="adet" value="1" min="1">
                        <button type="submit">Sepete Ekle</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Bu kategoride ürün bulunamadı.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
