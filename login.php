<?php
include 'connect.php';
session_start(); // Pastikan session dimulai sebelum ada output

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        $stmt = $conn->prepare("SELECT id_user, password FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Simpan session dengan nama yang mudah dipahami dan konsisten
                $_SESSION['user_id'] = $row['id_user'];

                // Redirect ke halaman utama
                header("Location: index.php");
                exit();
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Email tidak ditemukan.";
        }

        $stmt->close();
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg,#FFEF00,rgb(162, 56, 249));
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 450px;
            text-align: center;
        }
        .login-container img {
            width: 140px;
            height: auto;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 70%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 60%;
            padding: 10px;
            border: none;
            background:rgb(122, 234, 114);
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background:rgb(69, 29, 115);
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .register-button {
            width: 56%;
            margin-top: 10px;
            background: rgb(49, 132, 241);
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .register-button:hover {
            background: rgb(29, 79, 185);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="login.png" alt="Login"> 
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="submit">Login</button>
        </form>
        <a href="register.php" class="register-button">Register</a>
    </div>
</body>
</html>
