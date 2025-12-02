<?php

header("Content-Type: application/json; charset=utf-8");

require '../login/credentials.php'; // usa o mesmo config do projeto

try {
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $db_password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Busca texto aleatório
    $stmt = $pdo->query("SELECT conteudo FROM textos ORDER BY RAND() LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode([
            "status" => "erro",
            "texto" => ""
        ]);
        exit;
    }

    echo json_encode([
        "status" => "ok",
        "texto" => $row["conteudo"]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
    exit;
}
