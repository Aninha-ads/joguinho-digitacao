<?php
session_start(); // Inicia a sessão
require "conexao.php";

$login = $_POST['login'];
$senha = $_POST['senha'];
$senhaConfirmar = $_POST['senhaConfirmar'];

// ... (Restante da lógica de validação e inserção com header("Location: ...") e $_SESSION['mensagem'])
// ... (Lembre-se de usar password_hash() aqui)
if ($senha !== $senhaConfirmar) {
    $_SESSION['mensagem'] = "As senhas não coincidem!";
    header("Location: cadastro.php");
    exit;
}

// Verifica se usuário já existe
$sql = "SELECT * FROM usuarios WHERE login = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $login);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['mensagem'] = "Esse login já está em uso!";
    header("Location: cadastro.php");
    exit;
}

// Cria hash da senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Insere no banco
$sql = "INSERT INTO usuarios (login, senha) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $login, $senhaHash);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['mensagem'] = "Usuário cadastrado com sucesso!";
    header("Location: login.php");
} else {
    $_SESSION['mensagem'] = "Erro ao cadastrar usuário.";
    header("Location: cadastro.php");
}
exit;

?>
