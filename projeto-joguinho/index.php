<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>TapType</title>
</head>

<body class="default">

    <h1 class="title">TapType</h1>

    <!-- Bonequinho: Perfil se logado | Login se não logado -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="php/usuario/perfil.php" class="user-button">
            <img src="img/user.png">
        </a>
    <?php else: ?>
        <a href="php/login/login.php" class="user-button">
            <img src="img/user.png">
        </a>
    <?php endif; ?>

    <div class="menu-buttons">
        
        <!-- Jogar só funciona logado -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="php/game/game.php" class="button">Jogar</a>
        <?php else: ?>
            <a href="php/login/login.php" class="button">Jogar</a>
        <?php endif; ?>

        <a href="php/usuario/ranking.php" class="button">Ranking</a>
        <a href="php/usuario/ligas.php" class="button">Ligas</a>

        <!-- Se não estiver logado, aparece botão entrar -->
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="php/login/login.php" class="button">Entrar</a>
        <?php endif; ?>

    </div>

</body>
</html>












<?php
/*
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
*/
?>


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
