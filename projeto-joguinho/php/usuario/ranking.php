<?php
session_start();

// verifica login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../index.php");
    exit;
}
$currentUserId = $_SESSION["user_id"] ?? null;

// carregar credenciais
require_once __DIR__ . "/../login/credentials.php";
$host = $servername;
$dbUser = $username;
$dbPass = $db_password;
$database = $dbname;
$tableUsers = $table_users ?? 'users';

// conectar
try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . htmlspecialchars($e->getMessage()));
}

// === descobrir qual coluna em users contém o nome/username ===
// colunas candidatas (na ordem de preferência)
$candidateCols = ['username','user_name','nome','name','user','display_name','full_name','apelido'];

// pega lista de colunas reais da tabela users
try {
    $colsStmt = $pdo->query("SHOW COLUMNS FROM `{$tableUsers}`");
    $cols = $colsStmt->fetchAll(PDO::FETCH_COLUMN, 0); // nomes das colunas
} catch (PDOException $e) {
    // se deu erro ao inspecionar users, vamos usar fallback para partidas.user_name
    $cols = [];
}

// encontra primeira candidata que existe na tabela users
$nameCol = null;
foreach ($candidateCols as $c) {
    if (in_array($c, $cols, true)) {
        $nameCol = $c;
        break;
    }
}

// período (geral / weekly)
$period = $_GET['period'] ?? 'all';
$limit = 50;

// montar SQL dinamicamente: se encontramos $nameCol, selecionamos u.`$nameCol` AS username
// se não, usamos p.user_name AS username (fallback)
if ($nameCol) {
    $selectUsername = "u.`{$nameCol}` AS username";
    $joinSelectPart = ", {$selectUsername}";
} else {
    $selectUsername = "p.user_name AS username";
    $joinSelectPart = ", {$selectUsername}";
}

// montar WHERE para semanal
$whereWeekly = "p.criado_em >= (NOW() - INTERVAL 7 DAY)";

if ($period === 'weekly') {
    $sql = "
        SELECT
            p.id,
            p.user_id,
            p.pontos,
            p.palavras,
            p.dificuldade,
            p.resultado,
            p.criado_em
            {$joinSelectPart}
        FROM partidas p
        LEFT JOIN `{$tableUsers}` u ON u.id = p.user_id
        WHERE {$whereWeekly}
        ORDER BY p.pontos DESC
        LIMIT {$limit}
    ";
    $title = "Ranking da Semana";
} else {
    $sql = "
        SELECT
            p.id,
            p.user_id,
            p.pontos,
            p.palavras,
            p.dificuldade,
            p.resultado,
            p.criado_em
            {$joinSelectPart}
        FROM partidas p
        LEFT JOIN `{$tableUsers}` u ON u.id = p.user_id
        ORDER BY p.pontos DESC
        LIMIT {$limit}
    ";
    $title = "Ranking Geral";
}

// executar
try {
    $stmt = $pdo->query($sql);
    $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // em caso de erro na query, mostramos mensagem informativa e o SQL (útil para debugar)
    die("Erro ao consultar ranking: " . htmlspecialchars($e->getMessage()) . "<br><pre>{$sql}</pre>");
}

// HTML de saída
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($title) ?></title>
<link rel="stylesheet" href="../../style.css">
<style>
    body { font-family: Arial, sans-serif; background:#111; color:#eee; margin:0; padding:30px; }
    .wrap { max-width:1100px; margin:0 auto; }
    .nav { display:flex; gap:10px; align-items:center; margin-bottom:16px; }
    .btn { padding:8px 12px; background:#333; color:#fff; text-decoration:none; border-radius:6px; }
    .btn.active { background:#b134ff; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    th, td { padding:10px; text-align:center; border-bottom:1px solid #222; }
    th { background:#222; color:#fff; }
    tr.me { background: rgba(177,52,255,0.08); font-weight:700; }
    .small { font-size:0.9rem; color:#bbb; }
    .empty { text-align:center; padding:30px; color:#ccc; }
</style>
</head>
<body>
<div class="wrap">
    <h1 style="margin:0 0 8px;">🏆 <?= htmlspecialchars($title) ?></h1>

    <div class="nav">
        <a class="btn <?= $period === 'all' ? 'active' : '' ?>" href="ranking.php?period=all">Geral</a>
        <a class="btn <?= $period === 'weekly' ? 'active' : '' ?>" href="ranking.php?period=weekly">Semanal</a>
        <a class="btn" href="menu.php" style="margin-left:auto;">Voltar ao Menu</a>
    </div>

    <?php if (empty($ranking)): ?>
        <div class="empty">Nenhuma partida encontrada para esse período.</div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Posição</th>
                <th>Usuário</th>
                <th>Pontos</th>
                <th>Palavras</th>
                <th>Dificuldade</th>
                <th>Resultado</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $pos = 1;
            foreach ($ranking as $row):
                $userDisplay = $row['username'] ?? ($row['user_name'] ?? 'Anônimo');
                $pontos = (int)($row['pontos'] ?? 0);
                $palavras = (int)($row['palavras'] ?? 0);
                $dif = htmlspecialchars($row['dificuldade'] ?? '-');
                $res = htmlspecialchars($row['resultado'] ?? '-');
                $rawDate = $row['criado_em'] ?? null;
                $dateFmt = $rawDate ? date("d/m/Y H:i", strtotime($rawDate)) : '-';
                $isMe = ((string)$currentUserId !== '' && (string)$currentUserId === (string)($row['user_id'] ?? ''));
        ?>
            <tr class="<?= $isMe ? 'me' : '' ?>">
                <td><?= $pos ?></td>
                <td><?= htmlspecialchars($userDisplay) ?></td>
                <td><?= $pontos ?></td>
                <td><?= $palavras ?></td>
                <td class="small"><?= $dif ?></td>
                <td class="small"><?= $res ?></td>
                <td class="small"><?= $dateFmt ?></td>
            </tr>
        <?php
            $pos++;
            endforeach;
        ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
