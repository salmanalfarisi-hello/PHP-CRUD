<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container my-3 bg-dark text-white p-4 rounded-5">
    <h1>Edit Data Pegawai</h1>
    <?php
    require_once('config.php');

    // Memeriksa apakah permintaan adalah metode GET dan mengandung parameter 'id'
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
        $id_pegawai = $_GET['id'];

        // Mengambil data pegawai berdasarkan ID
        $sql = "SELECT * FROM pegawai WHERE id_pegawai = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(1, $id_pegawai, PDO::PARAM_INT); // Bind parameter ke pernyataan prepared
            $stmt->execute(); // Eksekusi pernyataan
            $pegawai = $stmt->fetch(PDO::FETCH_ASSOC); // Mengambil data sebagai asosiatif array
        }
    }
    ?>

    <!-- Form untuk mengedit data -->
    <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_pegawai" value="<?php echo $pegawai['id_pegawai']; ?>">
        <div class="form-group">
            <label for="nama">Nama Pegawai:</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $pegawai['nama_pegawai']; ?>" required>
        </div>
        <div class="form-group">
            <label for="tgl_lahir">Tanggal Lahir:</label>
            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?php echo $pegawai['tgl_lahir']; ?>" required>
        </div>
        <div class "form-group">
        <label for="foto">Foto Pegawai:</label>
        <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
</div>
<div class="form-group">
    <label for="keterangan">Keterangan:</label>
    <input class="form-control" id="keterangan" name="keterangan" rows="4" required>
</div>

<div class="form-group">
    <label for="id_jabatan">Jabatan:</label>
    <select class="form-control" id="id_jabatan" name="id_jabatan" required>
        <?php
        // Eksekusi query untuk mengambil data jabatan dari database
        $sql_jabatan = "SELECT * FROM jabatan";
        $result_jabatan = $conn->query($sql_jabatan);

        // Periksa apakah ada hasil (baris data) dari query
        if ($result_jabatan->rowCount() > 0) {

            // Jika ada data jabatan, mulai loop untuk menampilkan pilihan dalam dropdown
            while ($jabatan = $result_jabatan->fetch(PDO::FETCH_ASSOC)) {

                // Periksa apakah jabatan saat ini sama dengan jabatan yang dipilih sebelumnya
                $selected = ($jabatan['id_jabatan'] == $pegawai['id_jabatan']) ? 'selected' : '';

                // Tampilkan pilihan jabatan dalam elemen <option> dengan nilai dan teks yang sesuai
                echo "<option value='" . $jabatan['id_jabatan'] . "' $selected>" . $jabatan['nama_jabatan'] . "</option>";
            }
        }
        ?>
    </select>
</div>
<button type="submit" class="btn btn-primary">Update Data</button>
</form>
</div>
</body>
</html>
