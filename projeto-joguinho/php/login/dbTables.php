<?php
require 'credentials.php';
$conn = mysqli_connect($servername, $username, $db_password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Conectado ao MySQL!<br>";

$sql = "CREATE DATABASE IF NOT EXISTS $dbname"
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

mysqli_close($conn);

echo "<br><b>Tudo pronto!</br>";


?>