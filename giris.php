<?php
session_start();
include "baglanti.php";

$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email  = trim($_POST["email"]);
    $parola = trim($_POST["parola"]);

    $sorgu = "SELECT id, email, parola FROM musteriler WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($baglanti, $sorgu);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $sonuc = mysqli_stmt_get_result($stmt);

        if ($sonuc && mysqli_num_rows($sonuc) === 1) {
            $kullanici = mysqli_fetch_assoc($sonuc);

            if (password_verify($parola, $kullanici["parola"])) {
                $_SESSION["musteri_id"] = $kullanici["id"];
                $_SESSION["email"] = $kullanici["email"];

                header("Location: index.php"); 
                exit;
            } else {
                $hata = "Şifre yanlış!";
            }
        } else {
            $hata = "Bu e-posta ile kayıtlı bir hesap bulunamadı.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $hata = "Sorgu hatası: " . mysqli_error($baglanti);
    }

    mysqli_close($baglanti);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-box {
            width: 350px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            text-align: center;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #bbb;
            font-size: 15px;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #333;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 17px;
        }
        button:hover {
            background: #555;
        }
        .error {
            background: #ffdddd;
            color: #a00000;
            border: 1px solid #ff7a7a;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .register-link {
            margin-top: 15px;
            display: block;
            color: #333;
            font-size: 14px;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Giriş Yap</h2>
    <?php if (!empty($hata)) echo "<div class='error'>" . htmlspecialchars($hata) . "</div>"; ?>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="E-posta" required>
        <input type="password" name="parola" placeholder="Şifre" required>
        <button type="submit">Giriş Yap</button>
    </form>
    <a href="kayitol.php" class="register-link">Hesabın yok mu? Kayıt Ol</a>
</div>
</body>
</html>
