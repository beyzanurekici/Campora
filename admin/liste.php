<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../baglanti.php";


if(!isset($_SESSION['admin_id'])){
    header("Location: admin_giris.php");
    exit;
}

$urunler = mysqli_query($baglanti, "SELECT * FROM urunler ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ürün Listesi</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { padding: 10px; border: 1px solid #333; text-align: center; }
img { max-width: 100px; }
a { text-decoration: none; color: #007BFF; }
a:hover { text-decoration: underline; }
</style>
</head>
<body>

<h2>Ürün Listesi</h2>


<?php
if(isset($_GET['mesaj']) && $_GET['mesaj'] == 'urun_silindi'){
    echo "<p style='color:green;'>Ürün başarıyla silindi!</p>";
}
?>

<table>
<tr>
    <th>ID</th>
    <th>Ürün Adı</th>
    <th>Fiyat</th>
    <th>Stok</th>
    <th>Resim</th>
    <th>İşlemler</th>
</tr>

<?php
if(mysqli_num_rows($urunler) > 0){
    while($row = mysqli_fetch_assoc($urunler)){
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>".htmlspecialchars($row['urun_adi'])."</td>";
        echo "<td>{$row['fiyat']}₺</td>";
        echo "<td>{$row['stok']}</td>";

        // Resim sütunu
        echo "<td>";
        $dosyaYolu = "../uploads/" . $row['resim']; 
        $tarayiciYolu = "../uploads/" . $row['resim']; 
        if(!empty($row['resim']) && file_exists($dosyaYolu)){
            echo "<img src='{$tarayiciYolu}' alt='Ürün Resmi'>";
        } else {
            echo "Resim yok";
        }
        echo "</td>";

        echo "<td>
            <a href='duzenle.php?id={$row['id']}'>Düzenle</a> |
            <a href='sil.php?id={$row['id']}'>Sil</a>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Hiç ürün bulunamadı.</td></tr>";
}
?>
</table>

</body>
</html>
