<?php
require "functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $conn = connect_db();

    $name  = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $pass  = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO Users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $pass);

    if (mysqli_stmt_execute($stmt)) {
        echo "Usuário registrado com sucesso!";
    } else {
        echo "Erro ao registrar: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    disconnect_db($conn);

    header("Location: ../../index.html");
    exit;
}
?>

<form method="POST">
    <label>Nome:</label>
    <input type="text" name="name" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" required><br><br>

    <label>Senha:</label>
    <input type="password" name="password" required><br><br>

    <button type="submit">Cadastrar</button>
</form>
