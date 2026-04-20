<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../config/auth.php');

if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

$total = $conn->query("SELECT COUNT(*) as total FROM chamados")->fetch_assoc()['total'];
$abertos = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='aberto'")->fetch_assoc()['total'];
$pendentes = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='pendente'")->fetch_assoc()['total'];
$resolvidos = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='resolvido'")->fetch_assoc()['total'];
?>

<?php include('partials/header.php'); ?>
<?php include('partials/sidebar.php'); ?>

<div class="content">

    <div class="topbar">
        <?= $_SESSION['usuario']['nome'] ?>
    </div>

    <div class="row mb-4">
        <div class="col-md-3"><div class="card p-3 text-center"><h6>Total</h6><h3><?= $total ?></h3></div></div>
        <div class="col-md-3"><div class="card p-3 text-center"><h6>Abertos</h6><h3><?= $abertos ?></h3></div></div>
        <div class="col-md-3"><div class="card p-3 text-center"><h6>Pendentes</h6><h3><?= $pendentes ?></h3></div></div>
        <div class="col-md-3"><div class="card p-3 text-center"><h6>Resolvidos</h6><h3><?= $resolvidos ?></h3></div></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-3">
                <h6>Status</h6>

                <div class="grafico-rosca-container">
                    <canvas id="graficoStatus"></canvas>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
new Chart(document.getElementById('graficoStatus'), {
    type: 'doughnut',
    data: {
        labels: ['Abertos', 'Pendentes', 'Resolvidos'],
        datasets: [{
            data: [<?= $abertos ?>, <?= $pendentes ?>, <?= $resolvidos ?>]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
</script>

</body>
</html>