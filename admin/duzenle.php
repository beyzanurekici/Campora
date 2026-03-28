<?php
session_start();
include "../baglanti.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_giris.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: liste.php");
    exit;
}
$urun_id = (int)$_GET['id'];

$urun_sorgu = mysqli_prepare($baglanti, "SELECT * FROM urunler WHERE id=?");
mysqli_stmt_bind_param($urun_sorgu, "i", $urun_id);
mysqli_stmt_execute($urun_sorgu);
$urun_sonuc = mysqli_stmt_get_result($urun_sorgu);
$urun = mysqli_fetch_assoc($urun_sonuc);
mysqli_stmt_close($urun_sorgu);

$mesaj = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $urun_adi = trim($_POST['urun_adi']);
    $fiyat = trim($_POST['fiyat']);
    $stok = trim($_POST['stok']);
    $resim = trim($_POST['resim']); 

    if(empty($resim)){
        $mesaj = "Resim dosya yolunu giriniz!";
    } else {
        $guncelle = mysqli_prepare($baglanti, "UPDATE urunler SET urun_adi=?, fiyat=?, stok=?, resim=? WHERE id=?");
        mysqli_stmt_bind_param($guncelle,"sdisi",$urun_adi,$fiyat,$stok,$resim,$urun_id);

        if(mysqli_stmt_execute($guncelle)){
            $mesaj = "Ürün başarıyla güncellendi!";
            $urun['urun_adi']=$urun_adi;
            $urun['fiyat']=$fiyat;
            $urun['stok']=$stok;
            $urun['resim']=$resim;
        } else {
            $mesaj = "Hata: ".mysqli_error($baglanti);
        }
        mysqli_stmt_close($guncelle);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ürün Düzenle</title>
<style>
input { width: 100%; padding: 8px; margin: 5px 0; }
button { padding: 10px 20px; background: #333; color:white; border:none; border-radius:5px; cursor:pointer; }
button:hover { background: #555; }
img { max-width: 150px; display: block; margin: 10px 0; }
.success { color: green; }
.error { color: red; }
</style>
</head>
<body>
<h2>Ürün Düzenle</h2>

<?php if($mesaj) echo "<p class='".(empty($resim) ? "error" : "success")."'>$mesaj</p>"; ?>

<form method="POST">
    <label>Ürün Adı:</label>
    <input type="text" name="urun_adi" value="<?php echo htmlspecialchars($urun['urun_adi']); ?>" required>

    <label>Fiyat:</label>
    <input type="number" step="0.01" name="fiyat" value="<?php echo $urun['fiyat']; ?>" required>

    <label>Stok:</label>
    <input type="number" name="stok" value="<?php echo $urun['stok']; ?>" required>

    <label>Mevcut Resim:</label>
    <img src="../<?php echo htmlspecialchars($urun['resim']); ?>" alt="Ürün Resmi">

    <label>Yeni Resim Dosya Yolu (örn: uploads/urun1.jpg):</label>
    <input type="text" name="resim" value="<?php echo htmlspecialchars($urun['resim']); ?>" required>

    <button type="submit">Güncelle</button>
</form>

<p><a href="liste.php">Geri Dön</a></p>
</body>
</html>
