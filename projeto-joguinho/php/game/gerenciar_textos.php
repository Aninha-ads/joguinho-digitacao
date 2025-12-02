<?php
// CONEXÃO
$pdo = new PDO("mysql:host=localhost;dbname=jogo_digitacao;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$mensagem = "";

// ============ ADICIONAR NOVO TEXTO ============
if (isset($_POST['novo_texto'])) {
    $stmt = $pdo->prepare("INSERT INTO textos (conteudo) VALUES (:txt)");
    $stmt->execute([':txt' => $_POST['novo_texto']]);
    $mensagem = "Novo texto adicionado com sucesso!";
}

// ============ EDITAR TEXTO EXISTENTE ============
if (isset($_POST['editar_id']) && isset($_POST['editar_texto'])) {
    $stmt = $pdo->prepare("UPDATE textos SET conteudo = :conteudo WHERE id = :id");
    $stmt->execute([
        ':conteudo' => $_POST['editar_texto'],
        ':id' => $_POST['editar_id']
    ]);
    $mensagem = "Texto ID {$_POST['editar_id']} atualizado!";
}

// ============ DELETAR TEXTO ============
if (isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM textos WHERE id = :id");
    $stmt->execute([':id' => $_POST['delete_id']]);
    $mensagem = "Texto ID {$_POST['delete_id']} deletado com sucesso!";
}

// ============ BUSCAR TEXTO PARA EDIÇÃO ============
$textoEdicao = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM textos WHERE id = :id");
    $stmt->execute([':id' => $_GET['editar']]);
    $textoEdicao = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ============ LISTAR TODOS ============
$stmt = $pdo->query("SELECT id, LEFT(conteudo, 120) AS preview FROM textos ORDER BY id ASC");
$textos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Textos</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        h1 { color: #333; }
        .msg { background: #d4edda; border-left: 3px solid #28a745; padding: 10px; margin-bottom: 15px; }
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 6px; overflow: hidden; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; }
        th { background: #444; color: white; }
        tr:hover { background: #f9f9f9; }
        button { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; }
        .del { background: #d9534f; color: white; }
        .del:hover { background: #c9302c; }
        .edit { background: #0275d8; color: white; }
        .edit:hover { background: #025aa5; }
        .add { background: #5cb85c; color: white; margin-bottom: 15px; }
        .add:hover { background: #449d44; }
        textarea { width: 100%; height: 150px; }
        .box { background: white; padding: 15px; border-radius: 6px; margin-bottom: 15px; }
    </style>
</head>
<body>

<h1>Gerenciar Textos do Jogo</h1>

<?php if ($mensagem): ?>
    <div class="msg"><?= $mensagem ?></div>
<?php endif; ?>


<!-- ========== FORM PARA ADICIONAR NOVO TEXTO ========== -->
<div class="box">
    <h2>Adicionar Novo Texto</h2>

    <form method="post">
        <textarea name="novo_texto" required placeholder="Digite o novo texto aqui..."></textarea><br><br>
        <button class="add" type="submit">Adicionar Texto</button>
    </form>
</div>


<!-- ========== FORM PARA EDITAR TEXTO EXISTENTE ========== -->
<?php if ($textoEdicao): ?>
<div class="box">
    <h2>Editando Texto ID <?= $textoEdicao['id'] ?></h2>

    <form method="post">
        <input type="hidden" name="editar_id" value="<?= $textoEdicao['id'] ?>">
        <textarea name="editar_texto" required><?= htmlspecialchars($textoEdicao['conteudo']) ?></textarea><br><br>
        <button class="edit" type="submit">Salvar Alterações</button>
    </form>
</div>
<?php endif; ?>


<!-- ========== TABELA DE TEXTOS ========== -->
<table>
    <tr>
        <th>ID</th>
        <th>Prévia</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($textos as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= nl2br(htmlspecialchars($t['preview'])) ?>...</td>
            <td>
                <!-- EDITAR -->
                <a href="?editar=<?= $t['id'] ?>">
                    <button class="edit">Editar</button>
                </a>

                <!-- EXCLUIR -->
                <form method="post" style="display:inline;"
                      onsubmit="return confirm('Deseja excluir o texto ID <?= $t['id'] ?>?');">
                    <input type="hidden" name="delete_id" value="<?= $t['id'] ?>">
                    <button class="del" type="submit">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>


