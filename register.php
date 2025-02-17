<?php
include 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Pendaftaran gagal. Username atau email mungkin sudah digunakan.";
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
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, rgb(106, 241, 49), rgb(162, 56, 249));
        }
        .register-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 450px;
            text-align: center;
        }
        .register-container img {
            width: 140px;
            height: auto;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
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
            background: rgb(122, 234, 114);
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: rgb(69, 29, 115);
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .login-button {
            width: 56%;
            margin-top: 10px;
            background: rgb(49, 132, 241);
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .login-button:hover {
            background: rgb(29, 79, 185);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <img src="registeryyy.jpg" alt="Register"> 
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="submit">Register</button>
        </form>
        <a href="login.php" class="login-button">Login</a>
    </div>
</body>
</html>
