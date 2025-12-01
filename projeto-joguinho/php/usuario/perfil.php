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
    <link rel="stylesheet" href="../../style.css">
</head>

<body class="form-page">

<div class="form-shape-top"></div>
<div class="form-shape-bottom"></div>

<h1 class="form-title">Meu Perfil</h1>

<div class="form-container">

    <div class="profile-info">
        <p><strong>Nome:</strong> <?= htmlspecialchars($user["name"]) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>
    </div>

    <div class="profile-buttons">
        <button onclick="location.href='edit_perfil.php'">Editar Perfil</button>
        <button onclick="location.href='change_pass.php'">Trocar Senha</button>
        <button onclick="location.href='../login/logout.php'">Sair</button>
        <button onclick="location.href='../../menu.html'">Voltar ao Menu</button>
    </div>

</div>

</body>
</html>
