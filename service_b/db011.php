<?php
$host = 'localhost';
$dbname = 'AkbarFitriAndhika_2410511011_A_TugasPertemuan10';
$username = 'mahasiswa';
$password = 'akucintafik'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Koneksi database gagal: ' . $e->getMessage()]);
    exit;
}
?>