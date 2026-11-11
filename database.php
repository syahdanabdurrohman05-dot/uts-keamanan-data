<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "database";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi Enkripsi AES-256-CBC
function enkripsiData($plaintext, $kunci) {
    $cipher = "AES-256-CBC";
    $keyHash = hash('sha256', $kunci, true); // Kunci dijadikan 32 byte
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $ciphertext = openssl_encrypt($plaintext, $cipher, $keyHash, OPENSSL_RAW_DATA, $iv);
    // Gabungkan IV dan Ciphertext lalu ubah ke Base64 agar terbaca sebagai karakter acak
    return base64_encode($iv . $ciphertext);
}

// Fungsi Dekripsi AES-256-CBC
function dekripsiData($ciphertextBase64, $kunci) {
    $cipher = "AES-256-CBC";
    $keyHash = hash('sha256', $kunci, true);
    $data = base64_decode($ciphertextBase64);
    $ivLength = openssl_cipher_iv_length($cipher);
    
    $iv = substr($data, 0, $ivLength);
    $rawCiphertext = substr($data, $ivLength);
    
    // Dekripsi data
    $hasil = openssl_decrypt($rawCiphertext, $cipher, $keyHash, OPENSSL_RAW_DATA, $iv);
    
    // Jika kunci salah, OpenSSL mengembalikan false atau karakter biner acak/rusak
    if ($hasil === false) {
        // Hasilkan karakter biner acak yang menggambarkan dekripsi gagal/rusak
        return "x#@" . substr(md5($ciphertextBase64 . $kunci), 0, 8) . "!!";
    }
    return $hasil;
}
?>