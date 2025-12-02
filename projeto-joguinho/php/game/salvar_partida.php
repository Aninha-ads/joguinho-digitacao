<?php
session_start();

require __DIR__ . "/../login/credentials.php";

header("Content-Type: application/json; charset=utf-8");

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(["status"=>"error","msg"=>"JSON inválido"]);
    exit;
}

if (empty($_SESSION["user_id"])) {
    http_response_code(403);
    echo json_encode(["status"=>"error","msg"=>"Usuário não logado"]);
    exit;
}

$pontos      = (int)($input['pontos'] ?? 0);
$palavras    = (int)($input['palavras'] ?? 0);
$dificuldade = substr($input['dificuldade'] ?? '', 0, 20);
$resultado   = substr($input['resultado'] ?? 'desconhecido', 0, 20);

$user_id  = (int)$_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

try {
    $pdo = new PDO(
    "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
    $username,
    $db_password,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

    if (empty($_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(["status" => "error", "msg" => "Usuário não logado"]);
        exit;
    }

    $user_id  = (int)$_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];

    $stmt = $pdo->prepare("
    INSERT INTO partidas 
        (user_id, user_name, pontos, palavras, dificuldade, resultado)
    VALUES 
        (:uid, :un, :p, :w, :dif, :res)
");


    $stmt->execute([
        ':uid' => $user_id,
        ':un'  => $user_name,
        ':p'   => $pontos,
        ':w'   => $palavras,
        ':dif' => $dificuldade,
        ':res' => $resultado
    ]);

    echo json_encode(['status'=>'ok', 'id'=>$pdo->lastInsertId()]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','msg'=>$e->getMessage()]);
}
