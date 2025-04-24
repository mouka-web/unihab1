<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost", "root", "", "event");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $mail = trim($_POST['mail']);
    $password = $_POST['password'];
    $tel = trim($_POST['tel']);

    if ($username === '' || $mail === '' || $password === '' || $tel === '') {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ? OR mail = ?");
        $stmt->bind_param("ss", $username, $mail);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $error = "Nom d'utilisateur ou mail déjà utilisé.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO user (username, mail, password, tel) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $username, $mail, $hashed, $tel);
            $insert->execute();
            $insert->close();

            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('event.jpg'); /* Remplace avec ton image */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }

        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color:  #0056b3;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Inscription</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <label>Nom d'utilisateur:
                <input type="text" name="username" required>
            </label>
            <label>Mail:
                <input type="email" name="mail" required>
            </label>
            <label>Mot de passe:
                <input type="password" name="password" required>
            </label>
            <label>Téléphone:
                <input type="text" name="tel" required>
            </label>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html>
