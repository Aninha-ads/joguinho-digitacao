<?php
session_start();
if (!isset($_SESSION["user_id"])) { header("Location: ../../index.php"); exit; }

require_once "../login/credentials.php";

$ligaId = intval($_GET["id"] ?? 0);
$period = $_GET["period"] ?? "all";

if ($ligaId <= 0) { die("Liga inválida."); }

try {
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $db_password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // nome da liga
    $stmt = $pdo->prepare("SELECT nome, criado_em FROM ligas WHERE id=?");
    $stmt->execute([$ligaId]);
    $liga = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$liga) { die("Liga não encontrada."); }

    // ranking
    $where = ($period === "weekly")
        ? "p.criado_em >= (NOW() - INTERVAL 7 DAY)"
        : "p.criado_em >= " . $pdo->quote($liga["criado_em"]);

    $sql = "
        SELECT u.name AS username, p.pontos, p.palavras, p.dificuldade, p.resultado, p.criado_em
        FROM partidas p
        JOIN usuarios_ligas ul ON p.user_id = ul.user_id
        JOIN users u ON u.id = p.user_id
        WHERE ul.liga_id = ? AND $where
        ORDER BY p.pontos DESC
    ";
    $stmt2 = $pdo->prepare($sql);
    $stmt2->execute([$ligaId]);
    $ranking = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style_ligas.css">
<title>Ranking da Liga</title>
</head>
<body class="default">

<h1 class="title">Liga: <?= htmlspecialchars($liga["nome"]) ?></h1>

<div class="menu-buttons">
    <a class="button <?= $period === 'all' ? 'active' : '' ?>" href="ranking_liga.php?id=<?= $ligaId ?>&period=all">Geral</a>
    <a class="button <?= $period === 'weekly' ? 'active' : '' ?>" href="ranking_liga.php?id=<?= $ligaId ?>&period=weekly">Semanal</a>
</div>

<?php if (!$ranking): ?>
<p>Nenhuma partida encontrada.</p>
<?php else: ?>
<table>
<tr>
    <th>Posição</th>
    <th>Jogador</th>
    <th>Pontos</th>
    <th>Palavras</th>
    <th>Dif.</th>
    <th>Resultado</th>
    <th>Data</th>
</tr>
<?php $pos = 1;
foreach ($ranking as $r): ?>
<tr>
    <td><?= $pos++ ?></td>
    <td><?= htmlspecialchars($r["username"]) ?></td>
    <td><?= $r["pontos"] ?></td>
    <td><?= $r["palavras"] ?></td>
    <td><?= $r["dificuldade"] ?></td>
    <td><?= $r["resultado"] ?></td>
    <td><?= date("d/m/Y H:i", strtotime($r["criado_em"])) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<a href="minhas_ligas.php" class="button">Voltar</a>

</body>
</html>
