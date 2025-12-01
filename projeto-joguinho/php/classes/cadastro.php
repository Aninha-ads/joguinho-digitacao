<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro</h2>

    <?php
    // Lógica para exibir a mensagem (Flash Message)
    if (isset($_SESSION['mensagem'])) {
        echo '<p style="color: red; font-weight: bold;">' . htmlspecialchars($_SESSION['mensagem']) . '</p>';
        unset($_SESSION['mensagem']); // Remove a mensagem após exibir
    }
    ?>

    <form method="POST" action="processa_cadastro.php">
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" required>  


        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>  


        <label for="senhaConfirmar">Confirmar Senha:</label>
        <input type="password" name="senhaConfirmar" id="senhaConfirmar" required>  


        <input type="submit" value="Cadastrar" id="cadastrar" name="cadastrar">
    </form>
</body>
</html>





<?php
/*
require "conexao.php"; // arquivo com mysqli_connect

$login = $_POST['login'];
$senha = md5($_POST['senha']);  // mantém sua lógica, mesmo insegura
$senhaConfirmar = $_POST['senhaConfirmar'];

if (empty($login) || empty($senha) || empty($senhaConfirmar)) {
    $_SESSION['mensagem'] = "Preencha todos os campos.";
    header("Location: ../cadastro.html");
    exit;
}

if ($senha !== $senhaConfirmar) {
    $_SESSION['mensagem'] = "As senhas não coincidem.";
    header("Location: ../cadastro.html");
    exit;
}

// 6. Crie o hash seguro da senha
// **MUDANÇA CRÍTICA DE SEGURANÇA:** Substituindo md5() por password_hash()
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);


$sql = "SELECT login FROM usuarios WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Esse login já existe'); window.location.href='../cadastro.html';</script>";
    exit;
}

$sql = "INSERT INTO usuarios (login, senha) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $login, $senha);

if ($stmt->execute()) {
$_SESSION['mensagem'] = "Usuário cadastrado com sucesso! Faça login.";
    header("Location: ../login.html");
    exit;
} else {
   // Erro: Redireciona de volta para o cadastro
    $_SESSION['mensagem'] = "Erro ao cadastrar usuário. Tente novamente.";
    header("Location: ../cadastro.html");
    exit;
}
    */
?>