<?php
header("Content-Type: application/json; charset=utf-8");

// banco
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

    // recebe dados do fetch()
    $json = json_decode(file_get_contents("php://input"), true);

    if (!$json) {
        echo json_encode(["status" => "erro", "msg" => "JSON inválido"]);
        exit;
    }

    $usuario = $json["usuario"] ?? "anonimo";
    $pontos = $json["pontos"] ?? 0;
    $palavras = $json["palavras"] ?? 0;
    $dificuldade = $json["dificuldade"] ?? "normal";
    $resultado = $json["resultado"] ?? "desconhecido";

    // salvar no banco
    $stmt = $pdo->prepare("
        INSERT INTO partidas (usuario, pontos, palavras, combo, dificuldade, resultado) 
        VALUES (:usuario, :pontos, :palavras, :combo, :dificuldade, :resultado)
    ");

    $stmt->execute([
        ':usuario' => $usuario,
        ':pontos' => $pontos,
        ':palavras' => $palavras,
        ':combo' => $combo,
        ':dificuldade' => $dificuldade,
        ':resultado' => $resultado
    ]);

    echo json_encode(["status" => "ok", "msg" => "Partida salva"]);

    file_put_contents("debug_salvar.txt", file_get_contents("php://input"));

} catch (PDOException $e) {
    echo json_encode(["status" => "erro", "msg" => $e->getMessage()]);
}
