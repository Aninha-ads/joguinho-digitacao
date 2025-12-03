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

$stmt = $pdo->prepare("
    SELECT * 
    FROM partidas 
    WHERE user_id = ?
    ORDER BY data_jogo DESC
");
$stmt->execute([$_SESSION["user_id"]]);
$partidas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Relatório</title>
</head>
<body>

<h2>Minhas Partidas</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>Pontos</th>
        <th>Palavras</th>
        <th>Dificuldade</th>
        <th>Resultado</th>
        <th>Data</th>
    </tr>

<?php foreach ($partidas as $p): ?>
<tr>
    <td><?= $p["pontos"] ?></td>
    <td><?= $p["palavras"] ?></td>
    <td><?= $p["dificuldade"] ?></td>
    <td><?= $p["resultado"] ?></td>
    <td><?= $p["data_jogo"] ?></td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
