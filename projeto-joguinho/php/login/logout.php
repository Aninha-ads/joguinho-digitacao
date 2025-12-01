<?php
session_start();
require "funcitons.php";

if(isset($_SESSION["user_id"])) {
    $conn = connect_db();
    $id = $_SESSION["user_id"];

    mysqli_query($conn, "UPSATE Users SET last_logout_at = NOW() WHERE id = $id");
    disconnect_db($conn);
}

session_Destroy();

header("Location: login.php");
exit;
?>