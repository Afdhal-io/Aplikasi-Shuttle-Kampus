<?php
$host = "localhost";
$user = "root";      // ganti jika berbeda
$pass = "";          // isi jika MySQL kamu pakai password
$db   = "Shuttle_Kampus";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
