<?php
require "../login/functions.php";

$success = "";
$error = "";

// Processar envio do formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Verificar campos vazios
    if ($name === "" || $email === "" || $password === "") {
        $error = "Preencha todos os campos!";
    } else {

        $conn = connect_db();

        // Verificar se o email já existe
        $check = mysqli_prepare($conn, "SELECT id FROM Users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "Este Email já está cadastrado!";
        } else {
            // Inserir usuário
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_prepare($conn,
                "INSERT INTO Users (name, email, password) VALUES (?, ?, ?)"
            );

            mysqli_stmt_bind_param($insert, "sss", $name, $email, $hash);

            if (mysqli_stmt_execute($insert)) {
                $success = "Conta criada com sucesso!";
            } else {
                $error = "Erro ao cadastrar usuário!";
            }

            mysqli_stmt_close($insert);
        }

        mysqli_stmt_close($check);
        disconnect_db($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta</title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body class="form-page">

<div class="form-shape-top"></div>
<div class="form-shape-bottom"></div>

<h1 class="form-title">Criar Conta</h1>

<div class="form-container">

    <?php if ($error): ?>
        <p class="form-error"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="form-success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Nome</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Senha</label>
        <input type="password" name="password" required>

        <button type="submit" class="form-button">Cadastrar</button>
    </form>

    <button class="form-back" onclick="location.href='../login/login.php'">Voltar</button>

</div>

</body>
</html>
