<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

// Ambil data yang dikirim service A
$input = json_decode(file_get_contents('php://input'), true);

// Cek input lengkap atau tidak
if (!isset($input['ph']) || !isset($input['lembap_udara'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ph dan lembap_udara wajib diisi']);
    exit;
}

$ph = $input['ph'];
$lembap_udara = $input['lembap_udara'];

// Logika klasifikasi kondisi air hidroponik
if ($ph < 5.5) {
    $hasil = 'Terlalu Asam';
} elseif ($ph > 7.5) {
    $hasil = 'Terlalu Basa';
} elseif ($lembap_udara < 60) {
    $hasil = 'Kering';
} elseif ($lembap_udara > 90) {
    $hasil = 'Terlalu Lembap';
} else {
    $hasil = 'Ideal';
}

// Simpan ke database
$stmt = $pdo->prepare("INSERT INTO hasil_klasifikasi (ph, lembap_udara, hasil) VALUES (?, ?, ?)");
$stmt->execute([$ph, $lembap_udara, $hasil]);

// Kembalikan response
echo json_encode([ 'prediksi' => $hasil, 'nilai_confidence' => 0.85 ]);
?>