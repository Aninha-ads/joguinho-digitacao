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
    $liga_id = (int)$_POST["liga"];
    $chave = trim($_POST["chave"]);

    // Obter liga
    $stmt = $pdo->prepare("SELECT * FROM ligas WHERE id = ?");
    $stmt->execute([$liga_id]);
    $liga = $stmt->fetch();

    if (!$liga) {
        $msg = "Liga não encontrada.";
    } elseif (!password_verify($chave, $liga["chave"])) {
        $msg = "Palavra-chave incorreta.";
    } else {
        // Registrar usuário
        $pdo->prepare("
            INSERT IGNORE INTO ligas_usuarios (liga_id, user_id)
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
<title>Entrar na Liga</title>
</head>
<body>

<h2>Entrar em uma Liga</h2>

<form method="POST">
    Liga:<br>
    <select name="liga">
        <?php foreach ($ligas as $l): ?>
            <option value="<?= $l['id'] ?>"><?= $l['nome'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Palavra-chave:<br>
    <input type="password" name="chave"><br><br>

    <button type="submit">Entrar</button>
</form>

<p><?= $msg ?></p>

</body>
</html>
