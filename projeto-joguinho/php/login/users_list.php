<?php
require "functions.php";
$conn = connect_db();

$sql = "SELECT id, name, email, created_at FROM Users ORDER BY id DESC";
$res = mysqli_query($conn, $sql);
?>

<h2>Usuários Registrados</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Criado em</th>
    </tr>

    <?php while ($u = mysqli_fetch_assoc($res)) { ?>
        <tr>
            <td><?= $u["id"] ?></td>
            <td><?= $u["name"] ?></td>
            <td><?= $u["email"] ?></td>
            <td><?= $u["created_at"] ?></td>
        </tr>
    <?php } ?>
</table>

<?php disconnect_db($conn); ?>
