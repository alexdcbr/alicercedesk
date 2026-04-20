<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../config/auth.php');

if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $assunto = $_POST['assunto'];
    $descricao = $_POST['descricao'];
    $usuario_id = $_SESSION['usuario']['id'];

    $stmt = $conn->prepare("
        INSERT INTO chamados (assunto, descricao, solicitante_id, status)
        VALUES (?, ?, ?, 'aberto')
    ");
    $stmt->bind_param("ssi", $assunto, $descricao, $usuario_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<?php include('partials/header.php'); ?>
<?php include('partials/sidebar.php'); ?>

<div class="content">

    <div class="topbar">
        Novo Chamado
    </div>

    <div class="card p-4">

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Assunto</label>
                <input type="text" name="assunto" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Anexo</label>
                <input type="file" name="anexo" class="form-control">
            </div>

            <button class="btn btn-primary">
                Criar Chamado
            </button>

        </form>

    </div>

</div>

</body>
</html>