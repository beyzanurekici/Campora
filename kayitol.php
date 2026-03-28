<?php
session_start();
include "baglanti.php";

$hata = "";
$basarili = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $parola = trim($_POST["parola"]);
    $parola2 = trim($_POST["parola2"]);

    if ($parola !== $parola2) {
        $hata = "Şifreler eşleşmiyor!";
    } else {
        
        $kontrol = mysqli_prepare($baglanti, "SELECT id FROM musteriler WHERE email = ?");
        mysqli_stmt_bind_param($kontrol, "s", $email);
        mysqli_stmt_execute($kontrol);
        mysqli_stmt_store_result($kontrol);

        if (mysqli_stmt_num_rows($kontrol) > 0) {
            $hata = "Bu e-posta zaten kayıtlı!";
        } else {
            
            $hashliSifre = password_hash($parola, PASSWORD_DEFAULT);

            $ekle = mysqli_prepare($baglanti, "INSERT INTO musteriler (email, parola) VALUES (?, ?)");
            mysqli_stmt_bind_param($ekle, "ss", $email, $hashliSifre);

            if (mysqli_stmt_execute($ekle)) {
                $basarili = "Kayıt başarılı! Giriş yapabilirsiniz.";
            } else {
                $hata = "Kayıt sırasında hata oluştu: " . mysqli_error($baglanti);
            }

            mysqli_stmt_close($ekle);
        }
        mysqli_stmt_close($kontrol);
    }
    mysqli_close($baglanti);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
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
        .success {
            background: #ddffdd;
            color: #007700;
            border: 1px solid #7aff7a;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .login-link {
            margin-top: 15px;
            display: block;
            color: #333;
            font-size: 14px;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Kayıt Ol</h2>
    <?php 
    if (!empty($hata)) echo "<div class='error'>" . htmlspecialchars($hata) . "</div>"; 
    if (!empty($basarili)) echo "<div class='success'>" . htmlspecialchars($basarili) . "</div>"; 
    ?>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="E-posta" required>
        <input type="password" name="parola" placeholder="Şifre" required>
        <input type="password" name="parola2" placeholder="Şifre Tekrar" required>
        <button type="submit">Kayıt Ol</button>
    </form>
    <a href="giris.php" class="login-link">Zaten hesabın var mı? Giriş Yap</a>
</div>
</body>
</html>
