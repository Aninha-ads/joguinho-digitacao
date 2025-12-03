<?php
session_start();
if (!isset($_SESSION["user_id"])) { header("Location: ../../index.php"); exit; }
require_once "../login/credentials.php";

$userId = $_SESSION["user_id"];

try {
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $db_password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $sql = "SELECT ligas.id, ligas.nome, ligas.criada_em
            FROM ligas
            JOIN usuarios_ligas ON ligas.id = usuarios_ligas.liga_id
            WHERE usuarios_ligas.user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $ligas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/ligas.css">
<title>Minhas Ligas</title>
</head>
<body class="default">
<h1 class="title">Minhas Ligas</h1>

<?php if (!$ligas): ?>
<p>Nenhuma liga encontrada.</p>
<?php else: ?>
<ul>
    <?php foreach ($ligas as $l): ?>
    <li>
        <?= htmlspecialchars($l["nome"]) ?> (ID: <?= $l["id"] ?>)
        <a class="button" href="ranking_liga.php?id=<?= $l["id"] ?>">Ranking</a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<a href="ligas.php" class="button">Voltar</a>
</body>
</html>
