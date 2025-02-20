<?php
include ('connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $judul = $_POST['judul'] ?? '';
    $id_kategori = $_POST['id_kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Pastikan kategori dipilih
    if (empty($id_kategori)) {
        echo "<p class='alert alert-danger text-center'>Kategori harus dipilih!</p>";
    } else {
        // Koneksi ke database
        $conn = new mysqli("localhost", "root", "", "ukk2025");
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Query untuk menambahkan tugas
        $stmt = $conn->prepare("INSERT INTO tasks (id_kategori, task, description, status) VALUES (?, ?, ?, 'Belum Selesai')");
        $stmt->bind_param("iss", $id_kategori, $judul, $deskripsi);

        if ($stmt->execute()) {
            echo "<p class='alert alert-success text-center'>Tugas berhasil ditambahkan!</p>";
            header('Location: index.php');
            exit();
        } else {
            echo "<p class='alert alert-danger text-center'>Gagal menambahkan tugas!</p>";
        }

        $stmt->close();
        $conn->close();
    }
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
                <label class="form-label">Tugas:</label>
                <input type="text" class="form-control" name="judul" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kategori:</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <?php
                        // Koneksi database untuk mengambil kategori
                        $conn = new mysqli("localhost", "root", "", "ukk2025");
                        if ($conn->connect_error) {
                            die("Koneksi gagal: " . $conn->connect_error);
                        }

                        $categories_query = mysqli_query($conn, "SELECT * FROM categories");
                        if ($categories_query) {
                            while ($categories = mysqli_fetch_array($categories_query)) {
                                echo "<option value='" . $categories['id'] . "'>" . $categories['categories'] . "</option>";
                            }
                        }
                        $conn->close();
                    ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea class="form-control" name="deskripsi" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Simpan</button>
            <a href="index.php" class="btn btn-secondary w-100 mt-2">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
