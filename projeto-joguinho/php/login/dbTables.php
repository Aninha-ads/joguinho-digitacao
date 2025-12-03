<?php
require 'credentials.php';
$conn = mysqli_connect($servername, $username, $db_password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Conectado ao MySQL!<br>";

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($conn, $sql)) {
    echo "Banco '$dbname' criando ou já existente. <br>";
} else {
    echo "Erro ao criar banco: " . mysqli_error($conn);
}

mysqli_select_db($conn, $dbname);

$sql = "CREATE TABLE IF NOT EXISTS $table_users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login_at DATETIME NULL,
    last_logout_at DATETIME NULL
)";

if(mysqli_query($conn,$sql)) {
    echo "Tabela '$table_users' criada com sucesso!<br>";
} else {
    echo "Erro criando tabelas: " . mysqli_error($conn);
}

//tabela de textos do jogo
$sql_textos = "CREATE TABLE IF NOT EXISTS textos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql_textos)) {
    echo "Tabela 'textos' criada.<br>";
} else {
    echo "Erro criando tabela 'textos': " . mysqli_error($conn) . "<br>";
}

// tabela de partidas
$sql_partidas = "CREATE TABLE IF NOT EXISTS partidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    user_name VARCHAR(150) DEFAULT 'anonimo',

    pontos INT NOT NULL DEFAULT 0,
    palavras INT NOT NULL DEFAULT 0,
    dificuldade VARCHAR(20) DEFAULT NULL,
    resultado VARCHAR(20) DEFAULT NULL,
    data_jogo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $sql_partidas)) {
    echo "Tabela 'partidas' criada.<br>";
} else {
    echo "Erro criando tabela 'partidas': " . mysqli_error($conn) . "<br>";
}


/* parte das ligas */

$sql_ligas = "CREATE TABLE IF NOT EXISTS ligas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    palavra_chave VARCHAR(150) NOT NULL,
    criador_id INT NOT NULL,
    criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criador_id) REFERENCES $table_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql_ligas)) {
    echo "Tabela 'ligas' criada.<br>";
} else {
   echo "Erro criando tabela 'ligas': " . mysqli_error($conn) . "<br>";
}



$sql_ligasUsuario = "CREATE TABLE IF NOT EXISTS usuarios_ligas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    liga_id INT NOT NULL,
    inscrito_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES $table_users(id),
    FOREIGN KEY (liga_id) REFERENCES ligas(id),
    UNIQUE (user_id, liga_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql_ligasUsuario)) {
    echo "Tabela 'usuarios_ligas' criada.<br>";
} else {
    echo "Erro criando tabela 'usuarios_ligas': " . mysqli_error($conn) . "<br>";
}


mysqli_close($conn);

echo "<br><b>Tudo pronto! Tabelas criadas com sucesso (mysqli)</br>";


?>