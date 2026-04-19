<?php
session_start();
require_once('../config/database.php');
require_once('../config/auth.php');

if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
$perfil = $_SESSION['usuario']['perfil'];

// CLIENTE
if (isCliente()) {

    $stmt = $conn->prepare("
        SELECT c.*, u.nome as solicitante
        FROM chamados c
        JOIN usuarios u ON c.solicitante_id = u.id
        WHERE c.solicitante_id = ?
        ORDER BY c.id DESC
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    $total = $conn->query("SELECT COUNT(*) as total FROM chamados")->fetch_assoc()['total'];
    $abertos = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='aberto'")->fetch_assoc()['total'];
    $pendentes = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='pendente'")->fetch_assoc()['total'];
    $resolvidos = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='resolvido'")->fetch_assoc()['total'];

    $result = $conn->query("
        SELECT c.*, u.nome as solicitante
        FROM chamados c
        JOIN usuarios u ON c.solicitante_id = u.id
        ORDER BY c.id DESC
    ");
}

function classeStatus($status) {
    return 'status-' . $status;
}
?>

<?php include('partials/header.php'); ?>
<?php include('partials/sidebar.php'); ?>

<div class="content">

    <div class="topbar">
        <?= $_SESSION['usuario']['nome'] ?> (<?= $perfil ?>)
    </div>

    <?php if (!isCliente()): ?>

    <div class="row mb-4">
        <div class="col-md-3"><div class="card p-3 text-center"><h6>Total</h6><h3><?= $total ?></h3></div></div>
        <div class="col-md-3"><div class="card p-3 text-center"><h6>Abertos</h6><h3><?= $abertos ?></h3></div></div>
        <div class="col-md-3"><div class="card p-3 text-center"><h6>Pendentes</h6><h3><?= $pendentes ?></h3></div></div>
        <div class="col-md-3"><div class="card p-3 text-center"><h6>Resolvidos</h6><h3><?= $resolvidos ?></h3></div></div>
    </div>

    <?php endif; ?>

    <div class="card p-3">
        <h5>Chamados</h5>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Assunto</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['assunto'] ?></td>
                    <td>
                        <span class="badge <?= classeStatus($row['status']) ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="chamado_ver.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                            Abrir
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>

        </table>
    </div>

</div>

<?php include('partials/footer.php'); ?>