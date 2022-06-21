<?php

$server = 'localhost';
$user = 'id19028821_erdal';
$password = '*Aktarcim*1995*';
$database = 'id19028821_sifregiris';
$baglanti = mysqli_connect($server,$user,$password,$database);
mysql_set_charset('utf8', $baglanti);


if (!$baglanti) {
    echo "MySQL sunucu ile baglanti kurulamadi! </br>";
    echo "HATA: " . mysqli_connect_error();
    exit;
}

?>