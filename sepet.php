<?php
session_start();
include "baglanti.php"; // Veritabanı bağlantısı


if(!isset($_SESSION['musteri_id'])){
    header("Location: giris.php");
    exit;
}
$musteri_id = $_SESSION['musteri_id'];


if(isset($_GET['sil'])){
    $urun_id = (int)$_GET['sil'];
    if(isset($_SESSION['sepet'][$urun_id])){
        unset($_SESSION['sepet'][$urun_id]);
    }
    header("Location: sepet.php");
    exit;
}


$urunler = [];
if(isset($_SESSION['sepet']) && count($_SESSION['sepet']) > 0){
    $ids = implode(',', array_keys($_SESSION['sepet']));
    $sorgu = mysqli_query($baglanti, "SELECT * FROM urunler WHERE id IN ($ids)");
    while($u = mysqli_fetch_assoc($sorgu)){
        $urunler[] = $u;
    }
}


$iban = '';
$odeme_mesaj = '';
if(isset($_POST['odeme_yap']) && count($urunler) > 0){
    foreach($urunler as $u){
        $urun_id = $u['id'];
        $adet = $_SESSION['sepet'][$urun_id];
        $fiyat = $u['fiyat'];

        $stmt = mysqli_prepare($baglanti, "INSERT INTO siparisler (musteri_id, urun_id, adet, fiyat) VALUES (?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "iiid", $musteri_id, $urun_id, $adet, $fiyat);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    
    unset($_SESSION['sepet']);

        $iban = 'TR' . rand(10,99);
    for($i=0;$i<18;$i++){
        $iban .= rand(0,9);
    }
    $odeme_mesaj = "Ödemeniz başarılı! IBAN: $iban";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Sepetim</title>
<style>
body { font-family: Arial; background:#f9f9f9; margin:0; padding:20px; }
.container { max-width:900px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
h1 { text-align:center; margin-bottom:20px; }
.back { text-decoration:none; padding:6px 12px; background:#555; color:white; border-radius:5px; margin-bottom:15px; display:inline-block; }
.back:hover { background:#777; }

table { width:100%; border-collapse:collapse; margin-bottom:20px; }
th, td { padding:10px; border:1px solid #ccc; text-align:center; }
img { max-width:80px; border-radius:5px; }

button { padding:6px 12px; margin-top:5px; background:#333; color:white; border:none; border-radius:5px; cursor:pointer; }
button:hover { background:#555; }

.toplam { font-weight:bold; font-size:18px; text-align:right; margin-top:10px; }
.odeme { text-align:center; margin-top:20px; }
.iban { font-weight:bold; color:#c0392b; margin-top:10px; font-size:18px; }
.mesaj { background:#ddffdd; padding:10px; border:1px solid #7aff7a; border-radius:5px; margin-bottom:10px; text-align:center; }
</style>
</head>
<body>

<div class="container">
    <a href="index.php" class="back">Alışverişe Devam Et</a>

    <h1>Sepetim</h1>

    <?php if(isset($odeme_mesaj)) echo "<div class='mesaj'>$odeme_mesaj</div>"; ?>

    <?php if(count($urunler) > 0): ?>
        <table>
            <tr>
                <th>Resim</th>
                <th>Ürün</th>
                <th>Fiyat</th>
                <th>Adet</th>
                <th>Toplam</th>
                <th>İşlem</th>
            </tr>
            <?php 
            $toplamFiyat = 0;
            foreach($urunler as $u): 
                $adet = $_SESSION['sepet'][$u['id']] ?? 1;
                $tutar = $u['fiyat'] * $adet;
                $toplamFiyat += $tutar;
            ?>
            <tr>
                <td><img src="<?php echo htmlspecialchars($u['resim']); ?>" alt="<?php echo htmlspecialchars($u['urun_adi']); ?>"></td>
                <td><?php echo htmlspecialchars($u['urun_adi']); ?></td>
                <td><?php echo number_format($u['fiyat'],2,',','.'); ?> ₺</td>
                <td><?php echo $adet; ?></td>
                <td><?php echo number_format($tutar,2,',','.'); ?> ₺</td>
                <td><a href="?sil=<?php echo $u['id']; ?>">Sil</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="toplam">Toplam: <?php echo number_format($toplamFiyat,2,',','.'); ?> ₺</div>
    <?php else: ?>
        <p>Sepetinizde ürün bulunmamaktadır.</p>
    <?php endif; ?>

    <div class="odeme">
        <form method="POST">
            <button type="submit" name="odeme_yap">Ödeme Yap</button>
        </form>
    </div>
</div>

</body>
</html>
