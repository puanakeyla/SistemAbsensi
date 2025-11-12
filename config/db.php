<?php
$host = "localhost";
$user = "root"; // default XAMPP user
$pass = "";     // default kosong
$db   = "absensi_kampus";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
