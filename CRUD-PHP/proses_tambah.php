<?php
// Sertakan file konfigurasi database
include "config.php";

// Pastikan request adalah metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari formulir
    $nama = $_POST['nama'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $keterangan = $_POST['keterangan'];
    $id_jabatan = $_POST['id_jabatan'];

    // Proses unggah gambar
    $foto = $_FILES['foto'];
    $target_dir = "img/"; // Direktori untuk menyimpan gambar
    $target_file = $target_dir . basename($foto['name']); // Path lengkap file gambar
    $uploadOk = 1; // Inisialisasi status unggah

    // Periksa apakah file gambar sudah ada, jika ya, hapus (overwrite)
    if (file_exists($target_file)) {
        unlink($target_file); // Hapus file gambar lama
    }

    // Cek apakah unggah gambar berhasil
    if (move_uploaded_file($foto['tmp_name'], $target_file)) {

        // Jika unggah berhasil, tambahkan data pegawai ke database
        $sql = "INSERT INTO pegawai (nama_pegawai, tgl_lahir, foto, keterangan, id_jabatan) VALUES (:nama, :tgl_lahir, :foto, :keterangan, :id_jabatan)";

        $stmt = $conn->prepare($sql);


        // Periksa apakah pernyataan prepared berhasil
        if ($stmt) {

            // Bind parameter ke pernyataan prepared
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':tgl_lahir', $tgl_lahir);
            $stmt->bindParam(':foto', $target_file);
            $stmt->bindParam(':keterangan', $keterangan);
            $stmt->bindParam(':id_jabatan', $id_jabatan);

            // Eksekusi pernyataan
            if ($stmt->execute()) {

                // Jika eksekusi berhasil, tampilkan pesan sukses dan alihkan ke halaman utama
                echo "<script>

                    alert('Data berhasil ditambahkan');
                    
                    window.location.href = 'index.php';
                </script>";
            } else {
                // Jika eksekusi gagal, tampilkan pesan error
                echo "Error: " . $stmt->error;
            }

            $stmt->close(); // Tutup pernyataan prepared
        } else {

            // Jika gagal membuat pernyataan prepared, tampilkan pesan error
            echo "Error dalam persiapan statement: " . $conn->errorInfo()[2];
        }
    } else {
        // Jika unggah gambar gagal, tampilkan pesan error
        echo "Error: Gagal mengunggah file gambar.";
    }
}
?>
