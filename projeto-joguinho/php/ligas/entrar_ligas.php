<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    die("Você precisa estar logado.");
}

require __DIR__ . "/../login/credentials.php";

$pdo = new PDO(
    "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
    $username,
    $db_password,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // evita warnings
    $liga_id = isset($_POST["liga"]) ? (int)$_POST["liga"] : 0;
    $chave = $_POST["chave"] ?? "";

    // Obter liga
    $stmt = $pdo->prepare("SELECT * FROM ligas WHERE id = ?");
    $stmt->execute([$liga_id]);
    $liga = $stmt->fetch();

    if (!$liga) {
        $msg = "Liga não encontrada.";
    } elseif (!password_verify($chave, $liga["palavra_chave"])) {
        // usando campo correto
        $msg = "Palavra-chave incorreta.";
    } else {
        // Registrar usuário (tabela correta)
        $pdo->prepare("
            INSERT IGNORE INTO usuarios_ligas (liga_id, user_id)
            VALUES (?, ?)
        ")->execute([$liga_id, $_SESSION["user_id"]]);

        $msg = "Você entrou na liga!";
    }
}

// Listar todas as ligas
$ligas = $pdo->query("SELECT * FROM ligas ORDER BY nome")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/ligas.css">
<title>Entrar na Liga</title>
</head>
<body class="default">

<h2 class="title">Entrar em uma Liga</h2>

<form method="POST" class="form">

    <label>Liga:</label>
    <select name="liga">
        <?php foreach ($ligas as $l): ?>
            <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['nome']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Palavra-chave:</label>
    <input type="password" name="chave" required>

    <button type="submit" class="button">Entrar</button>
</form>

<p><?= $msg ?></p>

<!-- BOTÃO VOLTAR -->
<a href="ligas.php" class="button">Voltar</a>

</body>
</html>
