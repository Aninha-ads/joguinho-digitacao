<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style_ligas.css">
<title>Ligas</title>
</head>
<body class="default ligas">
<h1 class="title">Ligas</h1>

<div class="menu-buttons">
    <a href="criar_ligas.php" class="button">Criar Liga</a>
    <a href="entrar_ligas.php" class="button">Entrar em Liga</a>
    <a href="minhas_ligas.php" class="button">Minhas Ligas</a>
    <a href="../../index.php" class="button">Voltar</a>
</div>

</body>
</html>
