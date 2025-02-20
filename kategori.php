<?php
include 'connect.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Error: Anda harus login terlebih dahulu.");
}

$id_user = $_SESSION['user_id']; // ID user dari session

// **Tambah kategori**
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $nama_kategori = trim($_POST['nama_kategori'] ?? '');

    // Validasi input tidak boleh kosong
    if (empty($nama_kategori)) {
        die("Error: Nama kategori tidak boleh kosong.");
    }

    // Insert ke database
    $stmt = $conn->prepare("INSERT INTO categories (categories, id_user) VALUES (?, ?)");
    $stmt->bind_param("si", $nama_kategori, $id_user);

    if ($stmt->execute()) {
        header("Location: kategori.php");
        exit;
    } else {
        die("Error: Gagal menambahkan kategori. " . $conn->error);
    }
    $stmt->close();
}

// **Hapus kategori**
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ? AND id_user = ?");
    $stmt->bind_param("ii", $id, $id_user);

    if ($stmt->execute()) {
        header("Location: kategori.php");
        exit;
    } else {
        die("Error: Gagal menghapus kategori.");
    }
    $stmt->close();
}

// **Edit kategori**
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_category'])) {
    $id = $_POST['id'];
    $nama_kategori = $_POST['nama_kategori'];

    $stmt = $conn->prepare("UPDATE categories SET categories = ? WHERE id = ? AND id_user = ?");
    $stmt->bind_param("sii", $nama_kategori, $id, $id_user);

    if ($stmt->execute()) {
        header("Location: kategori.php");
        exit;
    } else {
        die("Error: Gagal mengedit kategori.");
    }
    $stmt->close();
}

// **Ambil data kategori berdasarkan user**
$stmt = $conn->prepare("SELECT * FROM categories WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    /* Gaya Drawer Profil */
    .open-drawer-btn {
            padding: 10px 20px;
            font-size: 18px;
            color: white;
            border: none;
            cursor: pointer;
            position: fixed;
            top: 20px;
            left: 20px;
        }
        .profile-drawer {
            position: fixed;
            top: 0;
            left: -300px; /* Menyembunyikan drawer pada awalnya */
            width: 300px;
            height: 100%;
            background-color: #333;
            box-shadow: 2px 0 5px rgba(0,0,0,0.3);
            color: white;
            transition: left 0.3s ease;
            z-index: 999;
        }
        .close-drawer-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            margin-bottom: 20px;
            width: 100%;
        }

        .profile-drawer.open {
            left: 0;
        }
        .profile-image {
            width: 100px;
            border-radius: 50%;
        }
        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        
</style>
<body>
<div class="container mt-5 text-center">
    <h2 class="mb-4">Kategori</h2>

    <!-- Form Tambah Kategori -->
    <form method="post">
        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
        </div>
        <button type="submit" class="btn btn-primary" name="add_category">Tambah Kategori</button>
    </form>

    <hr>

    <!-- Tabel Kategori -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category['id']; ?></td>
                    <td><?= $category['categories']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $category['id']; ?>">Edit</button>
                        <a href="?delete=<?= $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal<?= $category['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Kategori</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $category['id']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kategori</label>
                                        <input type="text" class="form-control" name="nama_kategori" value="<?= $category['categories']; ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="edit_category">Simpan Perubahan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal Edit -->
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Tombol untuk membuka drawer -->
<button class="open-drawer-btn btn btn-primary m-3" onclick="toggleDrawer()">☰</button>
    <!-- Drawer Profil -->
    <div id="profile-drawer" class="profile-drawer p-3">
        <button class="close-drawer-btn btn btn-danger" onclick="toggleDrawer()">×</button>
        <div class="text-center">
            <img src="rai.jpg" alt="Foto Profil" class="profile-image">
            <h4><?php echo $_SESSION['user_name'] ?? 'Dimas Agung Prasetyo'; ?></h4>
            <p><?php echo $_SESSION['user_email'] ?? 'dimas@gmail.com'; ?></p>

            <!-- Menu Navigasi -->
            <div class="list-group my-3">
                <a href="index.php" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-home me-2"></i> Home
                </a>
                <a href="history.php" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-history me-2"></i> History
                </a>
                <a href="kategori.php" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-th-list me-2"></i> Kategori
                </a>
            </div>
            <a href="logout.php" class="btn btn-danger mt-3">Log Out</a>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>

    <script>
        function toggleDrawer() {
            document.getElementById('profile-drawer').classList.toggle('open');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
