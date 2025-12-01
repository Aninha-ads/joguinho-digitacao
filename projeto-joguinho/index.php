<?php
session_start();

// Se não estiver logado, vai para login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joguinho!!</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">

    <h1>TapType - Jogo de Digitação</h1>

    <p>Bem-vindo, <strong><?= htmlspecialchars($_SESSION["user_name"]) ?></strong>!</p>

    <a href="menu.php" class="button">Ir para o Menu</a>
    <a href="users_list.php" class="button">Lista de Usuários</a>
    <a href="logout.php" class="button">Sair</a>

</div>
</body>
</html>



<?php
/*
session_start();

// Verifica se o usuário está logado
$login_usuario = $_SESSION['login'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joguinho!!</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">

    <h1>TapType - Jogo de Digitação</h1>

    <?php if ($login_usuario): ?>

        <p>Bem-vindo, <strong><?php echo htmlspecialchars($login_usuario); ?></strong>!</p>

        <a href="menu.php" class="button">Ir para o Menu</a>
        <a href="logout.php" class="button">Sair</a>

    <?php else: ?>

        <p>Bem-vindo, convidado!</p>
        <p>Faça login para jogar e salvar sua pontuação.</p>

        <!-- Formulário de login REAL -->
        <form method="POST" action="processalogin.php">
            <input type="text" name="login" placeholder="Seu login" required>
            <input type="password" name="senha" placeholder="Sua senha" required>
            <button type="submit" name="entrar">Entrar</button>
        </form>

        <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>

    <?php endif; ?>

</div>
</body>
</html>
*/
