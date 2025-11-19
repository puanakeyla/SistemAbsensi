<?php
class MahasiswaModel {
    private $conn;
    private $table_name = "mahasiswa";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getMahasiswaByNIM($nim) {
        $query = "SELECT * FROM {$this->table_name} WHERE nim = :nim AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nim", $nim, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getFirstMahasiswa() {
        $query = "SELECT * FROM {$this->table_name} WHERE deleted_at IS NULL ORDER BY id ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getStatistikKehadiran($mahasiswa_id) {
        $query = "SELECT
                    COUNT(*) AS total_pertemuan,
                    SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
                    SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) AS izin,
                    SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
                    SUM(CASE WHEN status = 'Alpha' THEN 1 ELSE 0 END) AS alpha
                  FROM absensi
                  WHERE id_mahasiswa = :mahasiswa_id
                    AND deleted_at IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return [
                'total_pertemuan' => 0,
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpha' => 0,
            ];
        }

        foreach (['total_pertemuan','hadir','izin','sakit','alpha'] as $key) {
            if (!isset($result[$key]) || $result[$key] === null) {
                $result[$key] = 0;
            }
        }

        return $result;
    }

    public function getTotalMataKuliah($mahasiswa_id) {
        $query = "SELECT COUNT(DISTINCT mk.id) AS total
                  FROM mahasiswa_kelas mkls
                  INNER JOIN kelas k ON mkls.id_kelas = k.id AND k.deleted_at IS NULL
                  INNER JOIN mata_kuliah mk ON k.id_mk = mk.id AND mk.deleted_at IS NULL
                  WHERE mkls.id_mahasiswa = :mahasiswa_id AND mkls.deleted_at IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function getJadwalHariIni($mahasiswa_id, $tanggal = null) {
        $hari = $this->resolveHariEnum($tanggal);
        if ($hari === null) {
            return [];
        }

        $query = "SELECT
                    j.id,
                    j.hari,
                    j.jam_mulai,
                    j.jam_selesai,
                    j.lokasi_lat,
                    j.lokasi_long,
                    j.radius_meter,
                    mk.nama_mk,
                    mk.kode_mk,
                    k.nama_kelas
                  FROM jadwal j
                  INNER JOIN kelas k ON j.id_kelas = k.id AND k.deleted_at IS NULL
                  INNER JOIN mata_kuliah mk ON k.id_mk = mk.id AND mk.deleted_at IS NULL
                  INNER JOIN mahasiswa_kelas mkls ON mkls.id_kelas = k.id AND mkls.deleted_at IS NULL
                  WHERE mkls.id_mahasiswa = :mahasiswa_id
                    AND j.hari = :hari
                    AND j.deleted_at IS NULL
                  ORDER BY j.jam_mulai";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id, PDO::PARAM_INT);
        $stmt->bindParam(":hari", $hari, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatusAbsensiHariIni($mahasiswa_id, $tanggal = null) {
        $hari = $this->resolveHariEnum($tanggal);
        if ($hari === null) {
            return [];
        }

        $query = "SELECT
                    j.id AS jadwal_id,
                    mk.nama_mk,
                    k.nama_kelas,
                    j.jam_mulai,
                    j.jam_selesai,
                    a.status,
                    a.tanggal
                  FROM jadwal j
                  INNER JOIN kelas k ON j.id_kelas = k.id AND k.deleted_at IS NULL
                  INNER JOIN mata_kuliah mk ON k.id_mk = mk.id AND mk.deleted_at IS NULL
                  INNER JOIN mahasiswa_kelas mkls ON mkls.id_kelas = k.id AND mkls.deleted_at IS NULL
                  LEFT JOIN absensi a ON a.id_jadwal = j.id
                    AND a.id_mahasiswa = :mahasiswa_id
                    AND a.tanggal = :tanggal
                    AND a.deleted_at IS NULL
                  WHERE mkls.id_mahasiswa = :mahasiswa_id
                    AND j.hari = :hari
                    AND j.deleted_at IS NULL
                  ORDER BY j.jam_mulai";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id, PDO::PARAM_INT);
        $stmt->bindValue(":tanggal", $tanggal ? date('Y-m-d', strtotime($tanggal)) : date('Y-m-d'));
        $stmt->bindParam(":hari", $hari, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNotifikasi($mahasiswa_id, $limit = 5) {
        $query = "SELECT id, type, message, is_read, created_at
                  FROM notifikasi
                  WHERE id_mahasiswa = :mahasiswa_id
                  ORDER BY created_at DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mahasiswa_id", $mahasiswa_id, PDO::PARAM_INT);
        $stmt->bindValue(":limit", (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function resolveHariEnum($tanggal = null) {
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $dayName = $tanggal ? date('l', strtotime($tanggal)) : date('l');
        return $hariMap[$dayName] ?? null;
    }
}
?>
