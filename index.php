<<<<<<< HEAD
=======
<?php
// Misalnya, data pengguna diambil dari session
session_start();

// Contoh data pengguna
if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = "Dimas Agung Prasetyo";
    $_SESSION['user_email'] = "Dimasagung@gmail.com";
    $_SESSION['user_picture'] = "rai.jpg"; // Gambar profil
}

// Fungsi untuk logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>

>>>>>>> b14eac3 (ukk ke 1)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Document</title>
</head>
<body>
    
</body>
</html>
=======
    <title>ukk-dimas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Tombol untuk membuka drawer -->
    <button class="open-drawer-btn" onclick="toggleDrawer()">☰ Menu</button>

    <!-- Drawer Profil -->
    <div id="profile-drawer" class="profile-drawer">
        <div class="drawer-content">
            <button class="close-drawer-btn" onclick="toggleDrawer()">×</button>
            <div class="profile-info">
                <img src="rai.jpg" alt="Foto Profil" class="profile-image">
                <h2>Dimas Agung Prasetyo</h2>
                <p>Dimasagung@gmail.com</p>
                <a href="logout.php" class="logout-btn">Log Out</a>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
>>>>>>> b14eac3 (ukk ke 1)
