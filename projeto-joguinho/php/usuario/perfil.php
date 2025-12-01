<?php
require "../login/authenticate.php"; // garante login ativo
require "../login/functions.php";

$conn = connect_db();
$user_id = $_SESSION["user_id"];

// pega dados do usuário
$sql = "SELECT name, email FROM Users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

disconnect_db($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 16px;
            background: #6c4dad;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 6px;
        }
    </style>
</head>
<body>

<h1>Perfil do Usuário</h1>

<p><strong>Nome:</strong> <?= htmlspecialchars($user["name"]) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>

<hr>

<a href="edit_perfil.php" class="btn">Editar Perfil</a>
<a href="change_pass.php" class="btn">Trocar Senha</a>
<a href="../../menu.html" class="btn">Voltar ao Menu</a>

</body>
</html>
