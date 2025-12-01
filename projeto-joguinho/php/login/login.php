<?php
session_start();
require "functions.php";

$erro = ""; // variável para exibir mensagens

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $conn = connect_db();

    $email = trim($_POST["email"] ?? "");
    $pass  = $_POST["password"] ?? "";

    // Verifica se os campos estão vazios
    if ($email === "" || $pass === "") {
        $erro = "Preencha todos os campos!";
    } else {

        // Consulta segura
        $sql = "SELECT id, name, password FROM Users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($res)) {

            if (password_verify($pass, $user["password"])) {

                // Login OK
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];

                // Atualiza último login
                $upd = mysqli_prepare($conn, "UPDATE Users SET last_login_at = NOW() WHERE id = ?");
                mysqli_stmt_bind_param($upd, "i", $user["id"]);
                mysqli_stmt_execute($upd);

                disconnect_db($conn);

                header("Location: ../../menu.html");
                exit;
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "Email não encontrado!";
        }

        disconnect_db($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if ($erro !== ""): ?>
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Entrar</button>
</form>

</body>
</html>
