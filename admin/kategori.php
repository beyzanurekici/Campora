<?php
session_start();
include "../baglanti.php";


if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_giris.php");
    exit;
}

$hata = "";
$basarili = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kategori_adi = trim($_POST["kategori"]);

    if (!empty($kategori_adi)) {
        $ekle = mysqli_prepare($baglanti, "INSERT INTO kategori (kategori) VALUES (?)");
        mysqli_stmt_bind_param($ekle, "s", $kategori_adi);

        if (mysqli_stmt_execute($ekle)) {
            $basarili = "Kategori başarıyla eklendi!";
        } else {
            $hata = "Kategori eklenemedi: " . mysqli_error($baglanti);
        }

        mysqli_stmt_close($ekle);
    } else {
        $hata = "Kategori adı boş olamaz!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Kategori Yönetimi</title>
<style>
body { font-family: Arial; background:#f0f0f0; padding:20px; }
.container { background:white; padding:20px; border-radius:10px; max-width:500px; margin:auto; box-shadow:0 0 10px rgba(0,0,0,0.2); }
input { width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #bbb; }
button { padding:10px 20px; border:none; border-radius:5px; background:#333; color:white; cursor:pointer; }
button:hover { background:#555; }
.success { background:#ddffdd; padding:10px; border:1px solid #7aff7a; border-radius:5px; margin-bottom:10px; }
.error { background:#ffdddd; padding:10px; border:1px solid #ff7a7a; border-radius:5px; margin-bottom:10px; }
</style>
</head>
<body>

<div class="container">
    <h2>Kategori Ekle</h2>

    <?php if(!empty($basarili)) echo "<div class='success'>$basarili</div>"; ?>
    <?php if(!empty($hata)) echo "<div class='error'>$hata</div>"; ?>

    <form method="POST">
        <input type="text" name="kategori" placeholder="Kategori Adı" required>
        <button type="submit">Ekle</button>
    </form>

    <hr>

    <h3>Mevcut Kategoriler</h3>
    <ul>
        <?php
        $listele = mysqli_query($baglanti, "SELECT id, kategori FROM kategori ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($listele)) {
            echo "<li>ID: " . $row['id'] . " | " . htmlspecialchars($row['kategori']) . "</li>";
        }
        ?>
    </ul>
</div>

</body>
</html>
