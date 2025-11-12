<?php
class MahasiswaModel {
    private $conn;
    private $table_name = "mahasiswa";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getMahasiswaByNIM($nim) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nim = :nim";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nim", $nim);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStatistikKehadiran($mahasiswa_id) {
        $query = "SELECT 
                    COUNT(*) as total_pertemuan,
                    SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as alpha
                  FROM absensi 
                  WHERE mahasiswa_id = :mahasiswa_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getJadwalHariIni($mahasiswa_id) {
        $query = "SELECT j.*, mk.nama_matkul, d.nama_dosen 
                  FROM jadwal j
                  JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id
                  JOIN dosen d ON j.dosen_id = d.id
                  JOIN mahasiswa_kelas mk2 ON j.kelas_id = mk2.kelas_id
                  WHERE mk2.mahasiswa_id = :mahasiswa_id 
                  AND j.hari = DAYNAME(CURDATE())
                  ORDER BY j.waktu_mulai";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatusAbsensiHariIni($mahasiswa_id) {
        $query = "SELECT j.id, mk.nama_matkul, j.waktu_mulai, j.waktu_selesai, a.status
                  FROM jadwal j
                  JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id
                  JOIN mahasiswa_kelas mk2 ON j.kelas_id = mk2.kelas_id
                  LEFT JOIN absensi a ON j.id = a.jadwal_id AND a.mahasiswa_id = :mahasiswa_id
                  WHERE mk2.mahasiswa_id = :mahasiswa_id 
                  AND j.hari = DAYNAME(CURDATE())
                  ORDER BY j.waktu_mulai";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNotifikasi($mahasiswa_id) {
        $query = "SELECT * FROM notifikasi 
                  WHERE mahasiswa_id = :mahasiswa_id 
                  ORDER BY created_at DESC 
                  LIMIT 5";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>