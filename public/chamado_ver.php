<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../config/auth.php');

if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

// Buscar chamado
$stmt = $conn->prepare("
    SELECT c.*, u.nome as solicitante 
    FROM chamados c
    JOIN usuarios u ON c.solicitante_id = u.id
    WHERE c.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$chamado = $stmt->get_result()->fetch_assoc();

// Buscar mensagens
$msgs = $conn->query("
    SELECT m.*, u.nome 
    FROM mensagens m
    JOIN usuarios u ON m.usuario_id = u.id
    WHERE m.chamado_id = $id
    ORDER BY m.id ASC
");

// Enviar mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mensagem = $_POST['mensagem'];
    $usuario_id = $_SESSION['usuario']['id'];

    $stmt = $conn->prepare("
        INSERT INTO mensagens (chamado_id, usuario_id, mensagem)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iis", $id, $usuario_id, $mensagem);
    $stmt->execute();

    header("Location: chamado_ver.php?id=" . $id);
    exit;
}
?>

<?php include('partials/header.php'); ?>
<?php include('partials/sidebar.php'); ?>

<div class="content">

<!-- HEADER DO CHAMADO -->
<div class="card p-3 mb-3">
    <h5>#<?= $chamado['id'] ?> - <?= $chamado['assunto'] ?></h5>
    <small>
        Solicitante: <?= $chamado['solicitante'] ?> | 
        Status: <span class="badge status-<?= $chamado['status'] ?>">
            <?= ucfirst($chamado['status']) ?>
        </span>
    </small>

    <?php if ($chamado['status'] != 'resolvido'): ?>
        <a href="chamado_resolver.php?id=<?= $id ?>" 
           class="btn btn-success btn-sm mt-2">
            Marcar como Resolvido
        </a>
    <?php endif; ?>
</div>

<!-- CHAT -->
<div class="card p-3 mb-3 chat-box">

    <?php while($m = $msgs->fetch_assoc()): ?>

        <?php 
        $isUser = ($m['usuario_id'] == $_SESSION['usuario']['id']);
        ?>

        <div class="chat-message <?= $isUser ? 'me' : 'other' ?>">

            <div class="chat-bubble">
                <strong><?= $m['nome'] ?></strong><br>
                <?= nl2br($m['mensagem']) ?>
            </div>

        </div>

    <?php endwhile; ?>

</div>

<!-- FORM RESPOSTA -->
<div class="card p-3">

    <form method="POST">

        <textarea name="mensagem" 
                  class="form-control mb-2" 
                  rows="3"
                  placeholder="Digite sua resposta..."></textarea>

        <div class="d-flex justify-content-between align-items-center">

            <input type="file" class="form-control w-50">

            <button class="btn btn-primary">
                Enviar
            </button>

        </div>

    </form>

</div>

</div>

</body>
</html>