<?php
session_start(); // Inicia a sessão
require "conexao.php";

$login = $_POST['login'];
$senha_digitada = $_POST['senha'];

// ... (Restante da lógica de busca e verificação com password_verify() e header("Location: ..."))
$sql = "SELECT * FROM usuarios WHERE login = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $login);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['mensagem'] = "Usuário não encontrado!";
    header("Location: login.php");
    exit;
}

$usuario = mysqli_fetch_assoc($result);

if (!password_verify($senha_digitada, $usuario['senha'])) {
    $_SESSION['mensagem'] = "Senha incorreta!";
    header("Location: login.php");
    exit;
}

// Login OK
$_SESSION['usuario_id'] = $usuario['ID'];
$_SESSION['usuario_login'] = $usuario['login'];

header("Location: menu.html");
exit;
?>
