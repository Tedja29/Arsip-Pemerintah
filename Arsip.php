<?php
// Koneksi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'arsiparis_setwan';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Ambil data arsip
$stmt = $pdo->query("SELECT * FROM arsip ORDER BY created_at DESC");
$arsip_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung statistik
$total_arsip = $pdo->query("SELECT COUNT(*) FROM arsip")->fetchColumn();
$total_kategori = $pdo->query("SELECT COUNT(DISTINCT kategori) FROM arsip")->fetchColumn();
$diakses_bulan_ini = $pdo->query("SELECT COUNT(*) FROM arsip WHERE MONTH(created_at) = MONTH(CURRENT_DATE())")->fetchColumn();
$arsip_terbaru = $pdo->query("SELECT COUNT(*) FROM arsip WHERE DATE(created_at) = CURDATE()")->fetchColumn();
?>