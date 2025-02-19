<?php
session_start();
include 'connect.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$task_id = $_GET['id'];

// Ambil data tugas berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();
$stmt->close();

if (!$task) {
    header("Location: index.php");
    exit;
}

// Proses update tugas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edited_task'])) {
    $edited_task = trim($_POST['edited_task']);
    if (!empty($edited_task)) {
        $stmt = $conn->prepare("UPDATE tasks SET task = ? WHERE id = ?");
        $stmt->bind_param("si", $edited_task, $task_id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit Tugas</h2>
        <form method="POST" class="d-flex justify-content-center">
            <input type="text" name="edited_task" class="form-control w-50" value="<?php echo htmlspecialchars($task['task']); ?>" required>
            <button type="submit" class="btn btn-warning ms-2">Simpan</button>
        </form>
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </div>
</body>
</html>
