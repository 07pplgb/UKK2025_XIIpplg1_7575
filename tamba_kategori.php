<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kategori = $_POST['nama_kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Simpan ke database (contoh, sesuaikan dengan koneksi database Anda)
    $conn = new mysqli("localhost", "root", "", "ukk2025");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO categories (id, id_user, categories) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $id, $id_user, $categories);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<p class='alert alert-success text-center'>Kategori berhasil ditambahkan!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .floating-box {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="container-fluid p-5">
    <div class="card p-4 w-100">
        <h2 class="mb-4 text-center">Tambah Kategori</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Nama Kategori:</label>
                <input type="text" class="form-control" name="nama_kategori" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea class="form-control" name="deskripsi" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
