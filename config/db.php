<?php
// Konfigurasi Database
$db_host = "localhost";
$db_name = "absensi_kampus";
$db_user = "root";
$db_pass = "";

// Membuat koneksi MySQLi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8");

// Class Database untuk keperluan di masa depan (optional)
class Database {
    private $host = "localhost";
    private $db_name = "absensi_kampus";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>