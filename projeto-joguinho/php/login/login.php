<?php
session_start();
require "functions.php";

$error = "";

// Processar login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $error = "Preencha todos os campos!";
    } else {

        $conn = connect_db();

        $sql = "SELECT id, name, password FROM Users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($res)) {
            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];

                // atualizar último login
                $upd = mysqli_prepare($conn,
                    "UPDATE Users SET last_login_at = NOW() WHERE id = ?"
                );
                mysqli_stmt_bind_param($upd, "i", $user["id"]);
                mysqli_stmt_execute($upd);

                disconnect_db($conn);

                header("Location: ../../index.php");
                exit;
            }
        }

        $error = "Email ou senha inválidos!";
        disconnect_db($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body class="form-page">

<div class="form-shape-top"></div>
<div class="form-shape-bottom"></div>

<h1 class="form-title">Login</h1>

<div class="form-container">

    <?php if ($error): ?>
        <p class="form-error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Senha</label>
        <input type="password" name="password" required>

        <button type="submit" class="form-button">Entrar</button>
    </form>

    <button class="form-back" onclick="location.href='user_register.php'">
        Criar Conta
    </button>

</div>

</body>
</html>
