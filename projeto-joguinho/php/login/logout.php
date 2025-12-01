<?php
session_start();
require "functions.php";

$conn = connect_db();

// Se houver usuário logado, salva horário do logout
if (isset($_SESSION["user_id"])) {

    $id = $_SESSION["user_id"];

    // CORRIGIDO: UPDATE - EU VOU REMOVER POIS NÃO QUERO REGISTRAR QUANDO O USUÁRIO SAI HIHI
/*
    $sql = "UPDATE Users SET last_logout_at = NOW() WHERE id = $id";
    mysqli_query($conn, $sql);
*/
}

session_unset();
session_destroy();

disconnect_db($conn);

// redirecionar para login
header("Location: login.php");
exit;
?>
