<?php

$host = 'sql113.infinityfree.com'; 
$user = 'if0_43143021';            
$pass = 'iniakunaran';      
$db   = 'if0_43143021_semesta_buku'; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>