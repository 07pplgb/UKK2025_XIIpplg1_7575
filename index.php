<?php
session_start();
include 'connect.php'; // Menyertakan koneksi ke database

// Menambahkan tugas baru


// Menghapus tugas
if (isset($_GET['delete'])) {
    $task_index = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_index);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Mengubah status tugas
if (isset($_GET['toggle_status'])) {
    $task_index = $_GET['toggle_status'];
    $current_status = $_GET['status']; 
    $new_status = ($current_status == 'pending') ? 'completed' : 'pending';

    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $task_index);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Mengedit tugas
if (isset($_POST['edit_task_id']) && isset($_POST['edited_task'])) {
    $edit_task_id = $_POST['edit_task_id'];
    $edited_task = trim($_POST['edited_task']);
    if (!empty($edited_task)) {
        $stmt = $conn->prepare("UPDATE tasks SET task = ? WHERE id = ?");
        $stmt->bind_param("si", $edited_task, $edit_task_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Pencarian tugas
$filtered_tasks = [];
$sql = "SELECT * FROM tasks";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql .= " WHERE task LIKE '%" . $conn->real_escape_string($search_query) . "%'";
}
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $filtered_tasks[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ukk dimas</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* Gaya untuk kontainer utama agar kontennya rata tengah */
.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 80vh; /* Pastikan konten berada di tengah secara vertikal */
}

/* Gaya untuk daftar tugas */
.task-list {
    width: 100%;
    max-width: 800px; /* Batasan lebar */
    margin-top: 20px;
}

/* Gaya untuk tabel tugas agar rata tengah */
.table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

.table th, .table td {
    text-align: center;
    padding: 10px;
}

.table th {
    background-color: #f8f9fa;
}

/* Gaya untuk form agar berada di tengah */
form.d-flex {
    justify-content: center;
    width: 100%;
    max-width: 800px;
}

form.d-flex input {
    width: 75%;
}

/* Gaya tombol */
button {
    padding: 10px 20px;
}

/* Gaya untuk elemen profil */
.profile-drawer {
    position: fixed;
    top: 0;
    left: -300px;
    width: 300px;
    height: 100%;
    background-color: #333;
    color: white;
    transition: left 0.3s ease;
    z-index: 999;
    padding: 20px;
}

/* Menambahkan padding di bawah tabel agar tidak terlalu rapat */
.task-list table {
    margin-bottom: 20px;
}

    </style>
</head>
<body>
    <!-- Tombol untuk membuka drawer -->
    <button class="open-drawer-btn btn btn-primary m-3" onclick="toggleDrawer()">☰</button>

    <!-- Drawer Profil -->
    <div id="profile-drawer" class="profile-drawer p-3">
        <button class="close-drawer-btn btn btn-danger" onclick="toggleDrawer()">×</button>
        <div class="text-center">
            <img src="rai.jpg" alt="Foto Profil" class="profile-image">
            <h2><?php echo $_SESSION['user_name'] ?? 'Dimas Agung Prasetyo'; ?></h2>
            <p><?php echo $_SESSION['user_email'] ?? 'dimas@gmail.com'; ?></p>
            <a href="logout.php" class="btn btn-danger">Log Out</a>
        </div>
    </div>

    <div class="container">
    <h1 class="mt-3 text-center">To Do List</h1>

    <!-- Form untuk menambahkan tugas -->
    <form method="POST" class="d-flex justify-content-center">
        <a type="submit" href="tamba_tugas.php" class="btn btn-primary ms-2">Tambah tugas </a>
        <a type="submit" href="tamba_kategori.php" class="btn btn-primary ms-2">Tambah kategori</a>

    </form>

    <!-- Form Pencarian Tugas -->
    <form method="GET" class="d-flex justify-content-center mt-3">
        <input type="text" name="search" class="form-control w-50" value="<?php echo $search_query ?? ''; ?>" placeholder="Cari tugas...">
        <button type="submit" class="btn btn-secondary ms-2">Cari</button>
    </form>

    <!-- Daftar Tugas -->
    <div class="task-list">
        <?php if (count($filtered_tasks) > 0): ?>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Nama Tugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filtered_tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['task']); ?></td>
                            <td class="task-status">
                                <?php echo $task['status'] == 'pending' ? 'Belum Selesai' : 'Selesai'; ?>
                            </td>
                            <td>
                                <a href="?toggle_status=<?php echo $task['id']; ?>&status=<?php echo $task['status']; ?>" class="btn btn-warning">
                                    <?php echo $task['status'] == 'pending' ? 'Tandai Selesai' : 'Tandai Belum Selesai'; ?>
                                </a>
                                <a href="?delete=<?php echo $task['id']; ?>" class="btn btn-danger">Delete</a>
                                <!-- Tombol Edit -->
                                <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn btn-info">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Tidak ada tugas ditemukan.</p>
        <?php endif; ?>
    </div>

    <!-- Form Edit Tugas -->
    <?php if (isset($_GET['edit'])): ?>
        <?php
        $edit_id = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $task_to_edit = $result->fetch_assoc();
        $stmt->close();
        ?>
        <form method="POST" class="d-flex justify-content-center mt-3">
            <input type="text" name="edited_task" class="form-control w-50" value="<?php echo htmlspecialchars($task_to_edit['task']); ?>" required>
            <input type="hidden" name="edit_task_id" value="<?php echo $task_to_edit['id']; ?>">
            <button type="submit" class="btn btn-warning ms-2">Simpan Edit</button>
        </form>
    <?php endif; ?>
    
</div>


    <script>
        function toggleDrawer() {
            document.getElementById('profile-drawer').classList.toggle('open');
        }
    </script>
</body>
</html>
