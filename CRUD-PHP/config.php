<?php
// Informasi untuk koneksi ke database
$host = 'localhost';   // Alamat server database
$dbname = 'belajar';   // Nama database yang digunakan
$username = 'root';    // Nama pengguna database
$password = '';        // Kata sandi pengguna database (kosong pada contoh ini)

try {

    // Membuat objek PDO untuk koneksi ke database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Mengatur atribut PDO untuk menangani kesalahan
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pesan sukses jika koneksi berhasil
    // echo "Koneksi berhasil!";
} catch (PDOException $e) {

    // Menampilkan pesan kesalahan jika terjadi masalah saat koneksi
    echo "Koneksi atau query bermasalah: " . $e->getMessage() . "</br>";

    // Menghentikan eksekusi program jika terjadi kesalahan
    die();
}
?>
