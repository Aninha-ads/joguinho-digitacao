<?php
session_start();

// Se o usuário NÃO estiver logado, redireciona
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html");
    exit;
}
?>


<?php
/*
session_start();

if (isset($_SESSION["user_id"]) &&
    isset($_SESSION["user_name"]) &&
    isset($_SESSION["user_email"])) {

    $login = true;
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];
    $user_email = $_SESSION["user_email"];

} else {

    $login = false;
    $user_id = null;
    $user_name = null;
    $user_email = null;

}
*/
?>
