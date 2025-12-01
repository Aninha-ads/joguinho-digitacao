<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login de Usuário</title>
</head>
<body>

    <h2>Login</h2>

    <?php
    // Lógica para exibir a mensagem (Flash Message)
    if (isset($_SESSION['mensagem'])) {
        // Usei 'green' para mensagens de sucesso (como "Usuário cadastrado com sucesso!")
        echo '<p style="color: green; font-weight: bold;">' . htmlspecialchars($_SESSION['mensagem']) . '</p>';
        unset($_SESSION['mensagem']); // Remove a mensagem após exibir
    }
    ?>

    <form method="POST" action="processa_login.php">
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" required>  


        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>  


        <input type="submit" value="Entrar" id="entrar" name="entrar">  


        <a href="cadastro.php">Cadastre-se</a>
    </form>
</body>
</html>



<?php
/* o segundo
// 1. Inicie a sessão para usar o sistema de mensagens (Flash Messages)
session_start();

require "conexao.php";

$login = $_POST['login'];
$senha_digitada = $_POST['senha']; // Receba a senha em texto puro

// 2. Busque o usuário no banco de dados
// **MUDANÇA:** Não use a senha na busca inicial, apenas o login.
$sql = "SELECT login, senha FROM usuarios WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Usuário não encontrado
    $_SESSION['mensagem'] = "Login ou senha incorretos.";
    header("Location: ../login.php"); // Redireciona para a página de login (agora login.php)
    exit;
}

$usuario = $result->fetch_assoc();
$senha_hash_armazenada = $usuario['senha'];

// 3. Verifique a senha usando password_verify()
// **MUDANÇA CRÍTICA DE SEGURANÇA:** Substituindo md5() por password_verify()
if (password_verify($senha_digitada, $senha_hash_armazenada)) {
    // Login bem-sucedido
    $_SESSION['login'] = $login;
    // Limpa qualquer mensagem de erro anterior
    unset($_SESSION['mensagem']); 
    header("Location: ../index.php");
    exit;
} else {
    // Senha incorreta
    $_SESSION['mensagem'] = "Login ou senha incorretos.";
    header("Location: ../login.php"); // Redireciona para a página de login (agora login.php)
    exit;
}
*/

/* o primeiro
if ($result->num_rows <= 0) {
    echo "<script>alert('Login ou senha incorretos'); window.location.href='../login.html';</script>";
    exit;
} else {
    session_start();
    $_SESSION['login'] = $login;
    header("Location: ../index.php");
    exit;
}
?>
*/
