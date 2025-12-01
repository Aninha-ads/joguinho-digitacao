<?php
require "../login/authenticate.php";
require "../login/functions.php";

$conn = connect_db();
$user_id = $_SESSION['user_id'];

$success = false;
$error = false;
$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name === '' || $email === '') {
        $error = true;
        $msg = "Preencha todos os campos.";
    } else {
        // atualizar usando prepared statement
        $sql = "UPDATE " . $table_users . " SET name = ?, email = ?, updated_at = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
        } else {
            $error = true;
            $msg = mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// buscar dados atuais
$sql = "SELECT name, email FROM " . $table_users . " WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

disconnect_db($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Editar Perfil</title>
</head>
<body>
<h1>Editar Perfil</h1>

<?php if ($success): ?>
    <p style="color:green;">Dados atualizados com sucesso!</p>
<?php endif; ?>

<?php if ($error): ?>
    <p style="color:red;">Erro: <?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Nome:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required><br><br>

    <button type="submit">Salvar alterações</button>
</form>

<br>
<a href="perfil.php">Voltar</a>
</body>
</html>



