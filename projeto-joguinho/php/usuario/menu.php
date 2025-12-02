<?php
session_start();

// Se o usuário não estiver logado, redireciona
if (!isset($_SESSION['login'])) {
    header("Location: ../login/login.php");
    exit;
}

$usuario = htmlspecialchars($_SESSION['login']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - TapType</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="menu-container">

        <h1 class="title">TapType</h1>

        <p class="welcome">Bem-vindo(a), <strong><?php echo $usuario; ?></strong>!</p>

        <!-- Ícone do usuário levando ao perfil -->
        <a href="perfil.php" class="user-button">
            <img src="user.png" alt="Perfil">
        </a>

        <div class="menu-buttons">
            <a href="../game/game.php" class="button">Jogar</a>
            <a href="ranking.php" class="button">Ranking</a>
            <a href="ligas.php" class="button">Ligas</a>
        </div>

        <a class="logout" href="logout.php">Sair</a>
    </div>

</body>
</html>
