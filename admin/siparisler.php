<?php
session_start();
include "../baglanti.php"; // Admin klasöründen bağlanıyor

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_giris.php");
    exit;
}


$query = "
SELECT s.id AS siparis_id, m.email AS musteri_email, u.urun_adi, s.adet, s.fiyat, s.tarih, k.kategori
FROM siparisler s
JOIN musteriler m ON s.musteri_id = m.id
JOIN urunler u ON s.urun_id = u.id
JOIN kategori k ON u.kategori_id = k.id
ORDER BY s.tarih DESC
";
$siparisler = mysqli_query($baglanti, $query);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Admin - Siparişler</title>
<style>
body { font-family: Arial; background:#f0f0f0; margin:0; padding:20px; }
.container { max-width:1200px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.2); }
h1 { text-align:center; margin-bottom:20px; }
table { width:100%; border-collapse:collapse; }
th, td { padding:10px; border:1px solid #ccc; text-align:center; }
th { background:#333; color:white; }
tr:nth-child(even) { background:#f9f9f9; }
</style>
</head>
<body>

<div class="container">
    <h1>Tüm Siparişler</h1>
    <?php if(mysqli_num_rows($siparisler) > 0): ?>
    <table>
        <tr>
            <th>Sipariş ID</th>
            <th>Müşteri Email</th>
            <th>Ürün</th>
            <th>Kategori</th>
            <th>Adet</th>
            <th>Birim Fiyat</th>
            <th>Toplam Fiyat</th>
            <th>Tarih</th>
        </tr>
        <?php while($s = mysqli_fetch_assoc($siparisler)): ?>
        <tr>
            <td><?php echo $s['siparis_id']; ?></td>
            <td><?php echo htmlspecialchars($s['musteri_email']); ?></td>
            <td><?php echo htmlspecialchars($s['urun_adi']); ?></td>
            <td><?php echo htmlspecialchars($s['kategori']); ?></td>
            <td><?php echo $s['adet']; ?></td>
            <td><?php echo number_format($s['fiyat'],2,',','.'); ?> ₺</td>
            <td><?php echo number_format($s['fiyat'] * $s['adet'],2,',','.'); ?> ₺</td>
            <td><?php echo $s['tarih']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>Henüz sipariş yok.</p>
    <?php endif; ?>
</div>

</body>
</html>
