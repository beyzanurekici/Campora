<?php
session_start();
include "../baglanti.php";

$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email  = trim($_POST["email"]);
    $parola = trim($_POST["parola"]);

    $sorgu = "SELECT id, email, parola FROM admin WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($baglanti, $sorgu);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $sonuc = mysqli_stmt_get_result($stmt);

        if ($sonuc && mysqli_num_rows($sonuc) === 1) {
            $admin = mysqli_fetch_assoc($sonuc);

            if (password_verify($parola, $admin["parola"])) {
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_email"] = $admin["email"];
                header("Location: index.php"); 
                exit;
            } else {
                $hata = "Şifre yanlış!";
            }
        } else {
            $hata = "Bu e-posta ile kayıtlı bir admin bulunamadı.";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($baglanti);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Admin Giriş</title>
</head>
<body>
<h2>Admin Giriş</h2>
<?php if(!empty($hata)) echo "<p style='color:red;'>".htmlspecialchars($hata)."</p>"; ?>
<form method="POST">
    <input type="email" name="email" placeholder="E-posta" required><br><br>
    <input type="password" name="parola" placeholder="Şifre" required><br><br>
    <button type="submit">Giriş Yap</button>
</form>
</body>
</html>
