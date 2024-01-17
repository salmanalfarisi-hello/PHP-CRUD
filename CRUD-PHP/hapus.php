<?php

// Sertakan file konfigurasi database
require_once('config.php');

// Periksa apakah permintaan adalah metode GET dan parameter 'id' telah diset
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_pegawai = $_GET['id'];

    // Mengambil nama file foto yang akan dihapus
    $sql_select_foto = "SELECT foto FROM pegawai WHERE id_pegawai = ?";

    // Persiapkan pernyataan prepared untuk mengambil nama file foto
    $stmt_select_foto = $conn->prepare($sql_select_foto);

    if ($stmt_select_foto) {

        $stmt_select_foto->bindParam(1, $id_pegawai, PDO::PARAM_INT); // Bind parameter ke pernyataan prepared
        $stmt_select_foto->execute(); // Eksekusi pernyataan prepared

        // Dapatkan hasil query
        $result_select_foto = $stmt_select_foto->fetch(PDO::FETCH_ASSOC);

        // Ambil nama file foto dari hasil query
        $foto = $result_select_foto['foto'];

        // Proses penghapusan data pegawai
        $sql_delete = "DELETE FROM pegawai WHERE id_pegawai = ?";

        // Persiapkan pernyataan prepared untuk menghapus data pegawai
        $stmt_delete = $conn->prepare($sql_delete);

        if ($stmt_delete) {
            $stmt_delete->bindParam(1, $id_pegawai, PDO::PARAM_INT); // Bind parameter ke pernyataan prepared

            // Jika penghapusan berhasil
            if ($stmt_delete->execute()) {

                // Set ulang auto-increment untuk kolom id_pegawai menjadi 1
                $sql_reset_auto_increment = "ALTER TABLE pegawai AUTO_INCREMENT = 1";

                // Persiapkan pernyataan prepared untuk mengatur ulang auto-increment
                $stmt_reset_auto_increment = $conn->prepare($sql_reset_auto_increment);

                if ($stmt_reset_auto_increment) {

                    // Eksekusi pernyataan prepared untuk mengatur ulang auto-increment
                    $stmt_reset_auto_increment->execute();
                }

                // Hapus file foto jika ada
                if (!empty($foto) && file_exists($foto)) {
                    unlink($foto);
                }

                // Menampilkan pesan "Data berhasil dihapus" dengan JavaScript dan alihkan ke halaman utama
                echo "<script>
                        alert('Data berhasil dihapus');
                        window.location.href = 'index.php';
                      </script>";
                exit();
            } else {
                // Jika terjadi kesalahan dalam penghapusan, tampilkan pesan error
                echo "Error: " . $stmt_delete->errorInfo()[2];
            }
        } else {
            // Jika gagal membuat pernyataan prepared untuk penghapusan, tampilkan pesan error
            echo "Error dalam persiapan statement: " . $conn->error;
        }

        // Tutup pernyataan prepared
        $stmt_delete = null;
    } else {
        // Jika gagal membuat pernyataan prepared untuk mengambil foto, tampilkan pesan error
        echo "Error dalam persiapan statement: " . $conn->error;
    }

    // Tutup pernyataan prepared
    $stmt_select_foto = null;
}
?>