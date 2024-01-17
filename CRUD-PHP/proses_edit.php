<?php
// Sertakan file konfigurasi database
require_once('config.php');

// Pastikan request adalah metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id_pegawai = $_POST['id_pegawai'];
    $nama = $_POST['nama'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $keterangan = $_POST['keterangan'];
    $id_jabatan = $_POST['id_jabatan'];

    // Periksa apakah ada perubahan gambar
    $foto = $_FILES['foto'];
    // Jika ada gambar baru yang diunggah, proses unggah gambar
    if ($foto['error'] === 0) {
        $target_dir = "img/"; // Direktori untuk menyimpan gambar
        $target_file = $target_dir . basename($foto['name']); // Path lengkap file gambar

        // Periksa apakah file gambar sudah ada, jika ya, hapus (overwrite)
        if (file_exists($target_file)) {
            unlink($target_file); // Hapus file gambar lama
        }

        // Cek apakah unggah gambar berhasil
        if (move_uploaded_file($foto['tmp_name'], $target_file)) {

            // Update data pegawai dengan gambar baru
            $sql = "UPDATE pegawai SET nama_pegawai = ?, tgl_lahir = ?, foto = ?, keterangan = ?, id_jabatan = ? WHERE id_pegawai = ?";
            $stmt = $conn->prepare($sql);

            // Periksa apakah pernyataan prepared berhasil
            if ($stmt) {
                $stmt->bindParam(1, $nama);
                $stmt->bindParam(2, $tgl_lahir);
                $stmt->bindParam(3, $target_file);
                $stmt->bindParam(4, $keterangan);
                $stmt->bindParam(5, $id_jabatan);
                $stmt->bindParam(6, $id_pegawai);

                // Eksekusi pernyataan
                if ($stmt->execute()) {
                    // Jika eksekusi berhasil, tampilkan pesan sukses dan alihkan ke halaman utama
                    echo "<script>
                        alert('Data berhasil diupdate');
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
    } else {

        // Jika tidak ada gambar yang diunggah, update data pegawai tanpa mengubah gambar
        $sql = "UPDATE pegawai SET nama_pegawai = ?, tgl_lahir = ?, keterangan = ?, id_jabatan = ? WHERE id_pegawai = ?";
        $stmt = $conn->prepare($sql);

        // Periksa apakah pernyataan prepared berhasil
        if ($stmt) {
            $stmt->bindParam(1, $nama);
            $stmt->bindParam(2, $tgl_lahir);
            $stmt->bindParam(3, $keterangan);
            $stmt->bindParam(4, $id_jabatan);
            $stmt->bindParam(5, $id_pegawai);

            // Eksekusi pernyataan
            if ($stmt->execute()) {
                // Jika eksekusi berhasil, tampilkan pesan sukses dan alihkan ke halaman utama
                echo "<script>
                    alert('Data berhasil diupdate');
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
    }
}
?>
