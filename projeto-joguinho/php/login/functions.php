<?php
require "credentials.php";

function connect_db() {
    global $servername, $username, $db_password, $dbname;

    $conn = mysqli_connect($servername, $username, $db_password, $dbname);

    if (!$conn) {
        die("Erro na conexão ao MySQL: " . mysqli_connect_error());
    }

    return $conn;
}

function disconnect_db($conn) {
    if ($conn) {
        mysqli_close($conn);
    }
}
?>
