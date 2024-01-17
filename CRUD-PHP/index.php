<?php
// Sertakan file konfigurasi database
require_once('config.php');
// Inisialisasi variabel jumlah baris
$total_rows = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container my-3 bg-dark text-white p-4 rounded-5">
        <h1 class="text-center my-3">Data Pegawai</h1>

        <!-- Form untuk menambahkan data -->
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data" class="mb-3">

            <div class="form-group">
                <label for="nama">Nama Pegawai:</label>
                <input type="text" class="form-control mt-2" id="nama" name="nama" required>
            </div>

            <div class="form-group mt-4">
                <label for="tgl_lahir">Tanggal Lahir:</label>
                <input type="date" class="form-control mt-2" id="tgl_lahir" name="tgl_lahir" required>
            </div>

            <div class="form-group mt-4">
                <label for="foto">Foto Pegawai:</label>
                <input class="form-control mt-2" type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
            </div>

            <div class="form-group mt-4">
                <label for="keterangan">Keterangan:</label>
                <input class="form-control mt-2" id="keterangan" name="keterangan" required>
            </div>

            <div class="form-group mt-4">
                <label for="id_jabatan">Jabatan:</label>
                <select class="form-control mt-2" id="id_jabatan" name="id_jabatan" required>
                    <?php

                    // Eksekusi query untuk mengambil data jabatan dari database
                    $sql_jabatan = "SELECT * FROM jabatan";
                    $result_jabatan = $conn->query($sql_jabatan);

                    // Periksa apakah ada hasil (baris data) dari query
                    if ($result_jabatan->rowCount() > 0) {

                        // Jika ada data jabatan, mulai loop untuk menampilkan pilihan dalam dropdown
                        while ($jabatan = $result_jabatan->fetch()) {

                            // Periksa apakah jabatan saat ini sama dengan jabatan yang dipilih sebelumnya
                            $selected = ($jabatan['id_jabatan'] == $row['id_jabatan']) ? 'selected' : '';

                            // Tampilkan pilihan jabatan dalam elemen <option> dengan nilai dan teks yang sesuai
                            echo "<option value='" . $jabatan['id_jabatan'] . "' $selected>" . $jabatan['nama_jabatan'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Tambah Data</button>
        </form>

        <!-- Tabel untuk menampilkan data -->
        <p>Jumlah Baris:
            <?php

            // Eksekusi query untuk menghitung jumlah baris data pegawai
            $sql = "SELECT pegawai.*, jabatan.nama_jabatan FROM pegawai LEFT JOIN jabatan ON pegawai.id_jabatan = jabatan.id_jabatan";
            $result = $conn->query($sql);

            // Menghitung jumlah baris yang berhasil dideteksi
            $row_count = $result->rowCount();

            echo $row_count;

            ?>

        </p>

        <table class="table table-bordered table-striped table-dark table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID Pegawai</th>
                    <th>Nama Pegawai</th>
                    <th>Tanggal Lahir</th>
                    <th>Foto</th>
                    <th>Keterangan</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // Eksekusi query untuk mengambil data pegawai dan jabatan dari database
                $sql = "SELECT pegawai.*, jabatan.nama_jabatan FROM pegawai 
                        LEFT JOIN jabatan ON pegawai.id_jabatan = jabatan.id_jabatan";
                $result = $conn->query($sql);

                // Periksa apakah ada hasil (baris data) dari query
                if ($result->rowCount() > 0) {

                    // Jika ada data pegawai, mulai loop untuk menampilkan setiap baris data
                    while ($row = $result->fetch()) {

                        // Membuat baris tabel (<tr>) untuk setiap data pegawai
                        echo "<tr>";
                        echo "<td>" . $row['id_pegawai'] . "</td>";
                        echo "<td>" . $row['nama_pegawai'] . "</td>";
                        echo "<td>" . $row['tgl_lahir'] . "</td>";

                        // Menampilkan gambar pegawai dengan lebar dan tinggi tertentu
                        echo "<td><img src='" . $row['foto'] . "' width='100' height='100'></td>";
                        echo "<td>" . $row['keterangan'] . "</td>";
                        echo "<td>" . $row['nama_jabatan'] . "</td>";

                        // Menambahkan tombol "Hapus" dan "Edit" dengan tautan ke halaman terkait
                        echo "<td>";
                        echo "<a href='hapus.php?id=" . $row['id_pegawai'] . "' class='btn btn-danger'>Hapus</a> ";
                        echo "<a href='edit.php?id=" . $row['id_pegawai'] . "' class='btn btn-primary'>Edit</a>";
                        echo "</td>";

                        // Menutup baris tabel untuk data pegawai saat ini
                        echo "</tr>";
                    }
                } else {

                    // Jika tidak ada data pegawai, tampilkan pesan bahwa tidak ada data
                    echo "<tr><td colspan='7'>Tidak ada data pegawai.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <!-- Modal untuk menampilkan pesan sukses -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Sukses!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Data berhasil ditambahkan.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-e3PrpR+aSh6d1QrZtyZpb3vxZjMrU7ibtiFgms/6C5P6a/8WL3xx7lBO+3EmnJPd" crossorigin="anonymous"></script>

    <script>
        // Fungsi untuk menampilkan modal sukses
        function showSuccessModal() {
            $('#successModal').modal('show');
        }
    </script>
</body>

</html>