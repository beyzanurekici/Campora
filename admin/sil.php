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

$urun_sorgu = mysqli_prepare($baglanti,"SELECT urun_adi,resim FROM urunler WHERE id=?");
mysqli_stmt_bind_param($urun_sorgu,"i",$urun_id);
mysqli_stmt_execute($urun_sorgu);
$urun_sonuc = mysqli_stmt_get_result($urun_sorgu);
$urun = mysqli_fetch_assoc($urun_sonuc);
mysqli_stmt_close($urun_sorgu);

$mesaj="";

if(isset($_POST['sil'])){
    if(!empty($urun['resim']) && file_exists("../".$urun['resim'])){
        unlink("../".$urun['resim']);
    }

    $sil = mysqli_prepare($baglanti,"DELETE FROM urunler WHERE id=?");
    mysqli_stmt_bind_param($sil,"i",$urun_id);
    if(mysqli_stmt_execute($sil)){
        header("Location: liste.php?mesaj=urun_silindi");
        exit;
    } else {
        $mesaj = "Ürün silinemedi: ".mysqli_error($baglanti);
    }
    mysqli_stmt_close($sil);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ürün Sil</title>
</head>
<body>
<h2>Ürün Sil</h2>
<?php if($mesaj) echo "<p style='color:red;'>$mesaj</p>"; ?>

<p>Bu ürünü silmek istediğinize emin misiniz?</p>
<p><strong><?php echo htmlspecialchars($urun['urun_adi']); ?></strong></p>
<img src="../<?php echo htmlspecialchars($urun['resim']); ?>" alt="Ürün Resmi" style="max-width:150px;"><br><br>

<form method="POST">
<button type="submit" name="sil">Evet, Sil</button>
<a href="liste.php">İptal</a>
</form>
</body>
</html>
