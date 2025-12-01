<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'jogo_digitacao';

try {
    // conecta ao MySQL sem selecionar DB (para criar o DB)
    $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // cria o banco com charset
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // conecta ao banco recém-criado
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS textos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            conteudo TEXT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    $pdo->exec("
    CREATE TABLE IF NOT EXISTS partidas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) DEFAULT 'anonimo',
        pontos INT NOT NULL,
        palavras INT NOT NULL,
        combo INT NOT NULL,
        dificuldade VARCHAR(20),
        resultado VARCHAR(20),
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

    // textos do jogo
    $texts = [
""
    ];

    // prepara e insere cada texto com segurança
    $insert = $pdo->prepare("INSERT INTO textos (conteudo) VALUES (:conteudo)");
    $countInserted = 0;
    foreach ($texts as $t) {
        $insert->execute([':conteudo' => $t]);
        $countInserted++;
    }

    echo "Banco e tabela criados com sucesso. Textos inseridos: $countInserted";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
    exit;
}
