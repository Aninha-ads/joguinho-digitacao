<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'username');
define('DB_NAME', 'jogo_digitacao');

class DB {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, DB_USER, DB_PASS);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de conexão com o Banco de Dados: " . $e->getMessage());
        }
    }

    public stativ function getConnection() {
        if (!self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance->conn;
    }
 }

?>
