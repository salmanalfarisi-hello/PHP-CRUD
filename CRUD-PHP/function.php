<?php
//require_once('config.php');
//function hapusDataPegawai($id_pegawai) {
//    global $conn;
//    // Mengambil nama file foto yang akan dihapus
//    $sql_select_foto = "SELECT foto FROM pegawai WHERE id_pegawai = ?";
//
//    // Persiapkan pernyataan prepared untuk mengambil nama file foto
//    $stmt_select_foto = $conn->prepare($sql_select_foto);
//
//    if ($stmt_select_foto) {
//        $stmt_select_foto->bindParam(1, $id_pegawai, PDO::PARAM_INT); // Bind parameter ke pernyataan prepared
//        $stmt_select_foto->execute(); // Eksekusi pernyataan prepared
//
//        // Dapatkan hasil query
//        $result_select_foto = $stmt_select_foto->fetch(PDO::FETCH_ASSOC);
//
//        // Ambil nama file foto dari hasil query
//        $foto = $result_select_foto['foto'];
//
//        // Proses penghapusan data pegawai
//        $sql_delete = "DELETE FROM pegawai WHERE id_pegawai = ?";
//
//        // Persiapkan pernyataan prepared untuk menghapus data pegawai
//        $stmt_delete = $conn->prepare($sql_delete);
//
//        if ($stmt_delete) {
//            $stmt_delete->bindParam(1, $id_pegawai, PDO::PARAM_INT); // Bind parameter ke pernyataan prepared
//
//            // Jika penghapusan berhasil
//            if ($stmt_delete->execute()) {
//                // Set ulang auto-increment untuk kolom id_pegawai menjadi 1
//                $sql_reset_auto_increment = "ALTER TABLE pegawai AUTO_INCREMENT = 1";
//
//                // Persiapkan pernyataan prepared untuk mengatur ulang auto-increment
//                $stmt_reset_auto_increment = $conn->prepare($sql_reset_auto_increment);
//
//                if ($stmt_reset_auto_increment) {
//                    // Eksekusi pernyataan prepared untuk mengatur ulang auto-increment
//                    $stmt_reset_auto_increment->execute();
//                }
//
//                // Hapus file foto jika ada
//                if (!empty($foto) && file_exists($foto)) {
//                    unlink($foto);
//                }
//
//                return true; // Data berhasil dihapus
//            } else {
//                return false; // Gagal menghapus data
//            }
//        } else {
//            return false; // Gagal membuat pernyataan prepared untuk penghapusan
//        }
//
//        // Tutup pernyataan prepared
//        $stmt_delete = null;
//    } else {
//        return false; // Gagal membuat pernyataan prepared untuk mengambil foto
//    }
//
//    // Tutup pernyataan prepared
//    $stmt_select_foto = null;
//}
//
//function tambahData($nama, $tgl_lahir, $foto, $keterangan, $id_jabatan) {
//    global $conn;
//
//    // Proses unggah gambar
//    $target_dir = "img/"; // Direktori untuk menyimpan gambar
//    $target_file = $target_dir . basename($foto['name']); // Path lengkap file gambar
//    $uploadOk = 1; // Inisialisasi status unggah
//
//    // Periksa apakah file gambar sudah ada, jika ya, hapus (overwrite)
//    if (file_exists($target_file)) {
//        unlink($target_file); // Hapus file gambar lama
//    }
//
//    // Cek apakah unggah gambar berhasil
//    if (move_uploaded_file($foto['tmp_name'], $target_file)) {
//        // Jika unggah berhasil, tambahkan data pegawai ke database
//        $sql = "INSERT INTO pegawai (nama_pegawai, tgl_lahir, foto, keterangan, id_jabatan) VALUES (?, ?, ?, ?, ?)";
//        $stmt = $conn->prepare($sql);
//
//        // Periksa apakah pernyataan prepared berhasil
//        if ($stmt) {
//            $stmt->bindParam(1, $nama, PDO::PARAM_STR);
//            $stmt->bindParam(2, $tgl_lahir, PDO::PARAM_STR);
//            $stmt->bindParam(3, $target_file, PDO::PARAM_STR);
//            $stmt->bindParam(4, $keterangan, PDO::PARAM_STR);
//            $stmt->bindParam(5, $id_jabatan, PDO::PARAM_INT);
//
//            // Eksekusi pernyataan
//            if ($stmt->execute()) {
//                return true; // Data berhasil ditambahkan
//            } else {
//                return false; // Gagal menambahkan data
//            }
//        } else {
//            return false; // Gagal membuat pernyataan prepared
//        }
//    } else {
//        return false; // Gagal mengunggah file gambar
//    }
//}
//
//function editData($id_pegawai, $nama, $tgl_lahir, $foto, $keterangan, $id_jabatan) {
//    global $conn;
//
//    // Periksa apakah ada perubahan gambar
//    if ($foto['error'] === 0) {
//        // Jika ada gambar baru yang diunggah, proses unggah gambar
//        $target_dir = "img/"; // Direktori untuk menyimpan gambar
//        $target_file = $target_dir . basename($foto['name']); // Path lengkap file gambar
//
//        // Periksa apakah file gambar sudah ada, jika ya, hapus (overwrite)
//        if (file_exists($target_file)) {
//            unlink($target_file); // Hapus file gambar lama
//        }
//
//        // Cek apakah unggah gambar berhasil
//        if (move_uploaded_file($foto['tmp_name'], $target_file)) {
//            // Update data pegawai dengan gambar baru
//            $sql = "UPDATE pegawai SET nama_pegawai = ?, tgl_lahir = ?, foto = ?, keterangan = ?, id_jabatan = ? WHERE id_pegawai = ?";
//            $stmt = $conn->prepare($sql);
//
//            // Periksa apakah pernyataan prepared berhasil
//            if ($stmt) {
//                $stmt->bindParam(1, $nama, PDO::PARAM_STR);
//                $stmt->bindParam(2, $tgl_lahir, PDO::PARAM_STR);
//                $stmt->bindParam(3, $target_file, PDO::PARAM_STR);
//                $stmt->bindParam(4, $keterangan, PDO::PARAM_STR);
//                $stmt->bindParam(5, $id_jabatan, PDO::PARAM_INT);
//                $stmt->bindParam(6, $id_pegawai, PDO::PARAM_INT);
//
//                // Eksekusi pernyataan
//                if ($stmt->execute()) {
//                    return true; // Data berhasil diupdate
//                } else {
//                    return false; // Gagal mengupdate data
//                }
//            } else {
//                return false; // Gagal membuat pernyataan prepared
//            }
//        } else {
//            return false; // Gagal mengunggah file gambar
//        }
//    } else {
//        // Jika tidak ada gambar yang diunggah, update data pegawai tanpa mengubah gambar
//        $sql = "UPDATE pegawai SET nama_pegawai = ?, tgl_lahir = ?, keterangan = ?, id_jabatan = ? WHERE id_pegawai = ?";
//        $stmt = $conn->prepare($sql);
//
//        // Periksa apakah pernyataan prepared berhasil
//        if ($stmt) {
//            $stmt->bindParam(1, $nama, PDO::PARAM_STR);
//            $stmt->bindParam(2, $tgl_lahir, PDO::PARAM_STR);
//            $stmt->bindParam(3, $keterangan, PDO::PARAM_STR);
//            $stmt->bindParam(4, $id_jabatan, PDO::PARAM_INT);
//            $stmt->bindParam(5, $id_pegawai, PDO::PARAM_INT);
//
//            // Eksekusi pernyataan
//            if ($stmt->execute()) {
//                return true; // Data berhasil diupdate
//            } else {
//                return false; // Gagal mengupdate data
//            }
//        } else {
//            return false; // Gagal membuat pernyataan prepared
//        }
//    }
//}
//
//?>