<?php
include "../baglanti.php";

$email = "sinem@gmail.com";     
$parola = "12345";            
$hashliParola = password_hash($parola, PASSWORD_DEFAULT);

$ekle = mysqli_prepare($baglanti, "INSERT INTO admin (email, parola) VALUES (?, ?)");
mysqli_stmt_bind_param($ekle, "ss", $email, $hashliParola);
mysqli_stmt_execute($ekle);

echo "Admin kaydı eklendi!";
?>
