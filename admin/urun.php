<?php
session_start();
include "../baglanti.php";


if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}

$hata = "";
$basarili = "";


$kategori_sorgu = mysqli_query($baglanti, "SELECT id, kategori FROM kategori ORDER BY kategori ASC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kategori_id = $_POST['kategori_id'];
    $urun_adi = trim($_POST['urun_adi']);
    $aciklama = trim($_POST['aciklama']);
    $fiyat = trim($_POST['fiyat']);
    $stok = trim($_POST['stok']);
    $resim = trim($_POST['resim']); // Dosya yolu olarak alınacak

        if(empty($resim)){
        $hata = "Resim dosya yolunu giriniz!";
    }

    if(empty($hata)){
        $ekle = mysqli_prepare($baglanti, "INSERT INTO urunler (kategori_id, urun_adi, aciklama, fiyat, stok, resim) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($ekle, "issdis", $kategori_id, $urun_adi, $aciklama, $fiyat, $stok, $resim);

        if(mysqli_stmt_execute($ekle)){
            $basarili = "Ürün başarıyla eklendi!";
        } else {
            $hata = "Ürün eklenemedi: " . mysqli_error($baglanti);
        }

        mysqli_stmt_close($ekle);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ürün Ekle</title>
<style>
body { font-family: Arial; background:#f0f0f0; padding:20px; }
.container { background:white; padding:20px; border-radius:10px; max-width:700px; margin:auto; box-shadow:0 0 10px rgba(0,0,0,0.2); }
input, textarea, select { width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #bbb; }
button { padding:10px 20px; border:none; border-radius:5px; background:#333; color:white; cursor:pointer; }
button:hover { background:#555; }
.success { background:#ddffdd; padding:10px; border:1px solid #7aff7a; border-radius:5px; margin-bottom:10px; }
.error { background:#ffdddd; padding:10px; border:1px solid #ff7a7a; border-radius:5px; margin-bottom:10px; }
img { max-width:150px; margin-top:10px; display:block; }
</style>
</head>
<body>

<div class="container">
    <h2>Ürün Ekle</h2>

    <?php if(!empty($basarili)) echo "<div class='success'>$basarili</div>"; ?>
    <?php if(!empty($hata)) echo "<div class='error'>$hata</div>"; ?>

    <form method="POST">
        <label>Kategori Seç</label>
        <select name="kategori_id" required>
            <option value="">-- Kategori Seç --</option>
            <?php while($row = mysqli_fetch_assoc($kategori_sorgu)): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['kategori']); ?></option>
            <?php endwhile; ?>
        </select>

        <input type="text" name="urun_adi" placeholder="Ürün Adı" required>
        <textarea name="aciklama" placeholder="Ürün Açıklaması" rows="4" required></textarea>
        <input type="number" step="0.01" name="fiyat" placeholder="Fiyat" required>
        <input type="number" name="stok" placeholder="Stok" required>
        <input type="text" name="resim" placeholder="Resim Dosya Yolu (örn: uploads/urun1.jpg)" required>
        <button type="submit">Ürün Ekle</button>
    </form>
</div>

</body>
</html>
