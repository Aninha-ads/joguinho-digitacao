<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Conexão com banco
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'typing_game';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
                   $dbUser, $dbPass,
                   [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Busca as partidas do jogador logado
$stmt = $pdo->prepare("
    SELECT id, pontos, palavras, dificuldade, resultado, criado_em
    FROM partidas
    WHERE user_id = ?
    ORDER BY criado_em DESC
");

$stmt->execute([$user_id]);
$partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Partidas Anteriores</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body class = "default">
    <div class="jogos-anteriores">

        <h2 style="text-align:center;">Partidas Anteriores</h2>

        <table>
            <tr class="header-row">
                <th>ID</th>
                <th>Pontos</th>
                <th>Qtde de palavras</th>
                <th>Dificuldade</th>
                <th>Resultado</th>
                <th>Data</th>
            </tr>

            <?php if (count($partidas) === 0): ?>
            <tr>
                <td colspan="4">Nenhuma partida jogada ainda.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($partidas as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= $p['pontos'] ?></td>
                <td><?= $p['palavras'] ?></td>
                <td><?= $p['dificuldade'] ?></td>
                <td><?= $p['resultado'] ?></td>
                <td><?= date("d/m/Y H:i", strtotime($p['criado_em'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>

        </table>
        <br>
        <button onclick="window.location='../../index.php'">Voltar ao menu</button>
    </div>
</body>
</html>
