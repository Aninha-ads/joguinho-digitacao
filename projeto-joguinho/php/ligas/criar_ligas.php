<?php
session_start();
if (!isset($_SESSION["user_id"])) { header("Location: ../../index.php"); exit; }

require_once "../login/credentials.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $senha = trim($_POST["senha"] ?? "");
    $userId = $_SESSION["user_id"];

    if ($nome !== "" && $senha !== "") {
        try {
            $pdo = new PDO(
                "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
                $username,
                $db_password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // criar liga
            $stmt = $pdo->prepare("INSERT INTO ligas (nome, palavra_chave, criador_id) VALUES (?,?,?)");
            $stmt->execute([$nome, password_hash($senha, PASSWORD_DEFAULT), $userId]);

            $ligaId = $pdo->lastInsertId();

            // criador entra automaticamente
            $stmt2 = $pdo->prepare("INSERT INTO usuarios_ligas (user_id, liga_id) VALUES (?,?)");
            $stmt2->execute([$userId, $ligaId]);

            header("Location: ligas.php?ok=1");
            exit;

        } catch (PDOException $e) {
            die("Erro: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style_ligas.css">
<title>Criar Liga</title>
</head>
<body class="default ligas">
<h1 class="title">Criar Liga</h1>

<form method="POST" class="form">
    <label>Nome da Liga:</label>
    <input type="text" name="nome" required>

    <label>Palavra-chave:</label>
    <input type="password" name="senha" required>

    <button class="button" type="submit">Criar</button>
</form>

<a href="ligas.php" class="button">Voltar</a>
</body>
</html>
