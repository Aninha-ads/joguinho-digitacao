<?php
$pdo = new PDO("mysql:host=localhost;dbname=jogo_digitacao;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stmt = $pdo->query("SHOW TABLES");
echo "<h2>Tabelas encontradas:</h2>";
foreach ($stmt as $row) {
    echo "- " . $row[0] . "<br>";
}

echo "<hr>";

echo "<h2>Conteúdo da tabela 'partidas'</h2>";

try {
    $stmt = $pdo->query("SELECT * FROM partidas ORDER BY id DESC LIMIT 30");
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$dados) {
        echo "<p>Nenhum registro encontrado.</p>";
    } else {
        echo "<pre>";
        print_r($dados);
        echo "</pre>";
    }

} catch (Exception $e) {
    echo "<p><b>Erro ao ler a tabela partidas:</b> " . $e->getMessage() . "</p>";
}
