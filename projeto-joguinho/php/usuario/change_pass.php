<?php
require "../login/authenticate.php";
require "../login/functions.php";

$conn = connect_db();
$user_id = $_SESSION['user_id'];

$success = false;
$error = false;
$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST['current_password'] ?? null;
    $new = $_POST['new_password'] ?? null;
    $confirm = $_POST['confirm_password'] ?? null;

    if ($new === '' || $current === '' || $confirm === '') {
        $error = true;
        $msg = "Preencha todos os campos.";
    } elseif ($new !== $confirm) {
        $error = true;
        $msg = "Nova senha e confirmação não conferem.";
    } else {
        // buscar hash atual
        $sql = "SELECT password FROM " . $table_users . " WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if (!$row || !password_verify($current, $row['password'])) {
            $error = true;
            $msg = "Senha atual incorreta.";
        } else {
            $new_hash = password_hash($new, PASSWORD_DEFAULT);
            $upd = mysqli_prepare($conn, "UPDATE " . $table_users . " SET password = ?, updated_at = NOW() WHERE id = ?");
            mysqli_stmt_bind_param($upd, "si", $new_hash, $user_id);
            if (mysqli_stmt_execute($upd)) {
                $success = true;
            } else {
                $error = true;
                $msg = mysqli_error($conn);
            }
            mysqli_stmt_close($upd);
        }
    }
}

disconnect_db($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Trocar Senha</title>
</head>
<body>

<h1>Trocar Senha</h1>

<?php if ($success): ?>
    <p style="color:green;">Senha alterada com sucesso!</p>
<?php endif; ?>

<?php if ($error): ?>
    <p style="color:red;">Erro: <?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Senha atual:</label><br>
    <input type="password" name="current_password" required><br><br>

    <label>Nova senha:</label><br>
    <input type="password" name="new_password" required><br><br>

    <label>Confirmar nova senha:</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit">Trocar senha</button>
</form>

<br>
<a href="perfil.php">Voltar</a>

</body>
</html>


