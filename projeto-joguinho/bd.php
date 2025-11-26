<?php
header("Content-Type: application/json; charset=utf-8");

// Config do banco
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'jogo_digitacao';

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser, 
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Pega texto aleatório
    $stmt = $pdo->query("SELECT conteudo FROM textos ORDER BY RAND() LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode([
            "status" => "ok",
            "texto" => $row["conteudo"]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            "status" => "erro",
            "texto" => "Nenhum texto encontrado no banco!"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => "erro",
        "texto" => "Erro: " . $e->getMessage()
    ]);
}
?>
