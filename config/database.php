<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default Laragon password kosong
$db   = 'semesta_buku';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>