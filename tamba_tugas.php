<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $judul = $_POST['judul'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "ukk2025");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Query untuk menambahkan tugas
    $stmt = $conn->prepare("INSERT INTO tasks (category, task, description, status) VALUES (?, ?, ?, 'Belum Selesai')");
    $stmt->bind_param("sss", $kategori, $judul, $deskripsi);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p class='alert alert-success text-center'>Tugas berhasil ditambahkan!</p>";
        header('index.php');
    } else {
        echo "<p class='alert alert-danger text-center'>Gagal menambahkan tugas!</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="card p-4">
        <h2 class="mb-4 text-center">Tambah Tugas</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Judul:</label>
                <input type="text" class="form-control" name="judul" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kategori:</label>
                <input type="text" class="form-control" name="kategori" required>
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
