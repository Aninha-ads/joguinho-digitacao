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
    <link rel="stylesheet" href="../../style.css">
</head>

<body class="form-page">

<div class="form-shape-top"></div>
<div class="form-shape-bottom"></div>

<h1 class="form-title">Trocar Senha</h1>

<div class="form-container">

    <?php if ($success): ?>
        <p style="color:green; font-size:22px;">Senha alterada com sucesso!</p>
        <button class="form-button" onclick="location.href='perfil.php'">Voltar ao Perfil</button>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red; font-size:22px;"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">

        <div class="form-grid">

            <div class="form-group">
                <label>Senha atual</label>
                <input type="password" name="current_password" required>
            </div>

            <div class="form-group">
                <label>Nova senha</label>
                <input type="password" name="new_password" required>
            </div>

            <div class="form-group">
                <label>Confirmar nova senha</label>
                <input type="password" name="confirm_password" required>
            </div>

        </div>

        <button type="submit" class="form-button">Salvar nova senha</button>
    </form>

    <button class="form-button" style="background:#382A52; margin-top:20px"
        onclick="location.href='perfil.php'">Voltar</button>
    <?php endif; ?>

</div>

</body>
</html>

<?php

