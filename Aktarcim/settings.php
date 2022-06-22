<?php

$server = 'localhost';
$user = 'root';
$password = '';
$database = 'sifregiris';
$baglanti = mysqli_connect($server,$user,$password,$database);
mysql_set_charset('utf8', $baglanti);


if (!$baglanti) {
    echo "MySQL sunucu ile baglanti kurulamadi! </br>";
    echo "HATA: " . mysqli_connect_error();
    exit;
}

?>
