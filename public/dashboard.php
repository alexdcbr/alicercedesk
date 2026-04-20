<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../config/auth.php');

if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
$perfil = $_SESSION['usuario']['perfil'];

// KPIs
$total = $conn->query("SELECT COUNT(*) as total FROM chamados")->fetch_assoc()['total'];
$abertos = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='aberto'")->fetch_assoc()['total'];
$pendentes = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='pendente'")->fetch_assoc()['total'];
$resolvidos = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE status='resolvido'")->fetch_assoc()['total'];

// SLA
$sla = $conn->query("
    SELECT AVG(TIMESTAMPDIFF(HOUR, criado_em, resolvido_em)) as media
    FROM chamados
    WHERE resolvido_em IS NOT NULL
")->fetch_assoc()['media'];

$sla_display = $sla ? round($sla, 1) . 'h' : '-';

// FRT
$frt = $conn->query("
    SELECT AVG(TIMESTAMPDIFF(MINUTE, criado_em, primeira_resposta_em)) as media
    FROM chamados
    WHERE primeira_resposta_em IS NOT NULL
")->fetch_assoc()['media'];

$frt_display = $frt ? round($frt) . ' min' : '-';

// Evolução
$datas = [];
$valores = [];

$q = $conn->query("
    SELECT DATE(criado_em) as data, COUNT(*) as total
    FROM chamados
    GROUP BY DATE(criado_em)
");

while ($row = $q->fetch_assoc()) {
    $datas[] = $row['data'];
    $valores[] = $row['total'];
}

// Lista
$result = $conn->query("SELECT * FROM chamados ORDER BY id DESC");
?>

<?php include('partials/header.php'); ?>
<?php include('partials/sidebar.php'); ?>

<div class="content">

    <div class="topbar">
        <?= $_SESSION['usuario']['nome'] ?> (<?= $perfil ?>)
    </div>

    <!-- KPIs -->
    <div class="row mb-4">

        <div class="col-md-2">
            <div class="card p-3 text-center">
                <h6>Total</h6>
                <h3><?= $total ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 text-center">
                <h6>Abertos</h6>
                <h3><?= $abertos ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 text-center">
                <h6>Pendentes</h6>
                <h3><?= $pendentes ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 text-center">
                <h6>Resolvidos</h6>
                <h3><?= $resolvidos ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 text-center">
                <h6>SLA</h6>
                <h3><?= $sla_display ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card p-3 text-center">
                <h6>1ª Resp</h6>
                <h3><?= $frt_display ?></h3>
            </div>
        </div>

    </div>

    <!-- GRÁFICOS -->
    <div class="row mb-4">

        <!-- ROSCA -->
        <div class="col-md-6">
            <div class="card p-3">
                <h6>Status</h6>

                <div class="grafico-rosca-container">
                    <canvas id="graficoStatus"></canvas>
                </div>

            </div>
        </div>

        <!-- LINHA -->
        <div class="col-md-6">
            <div class="card p-3">
                <h6>Evolução</h6>
                <canvas id="graficoLinha"></canvas>
            </div>
        </div>

    </div>

    <!-- LISTA -->
    <div class="card p-3">
        <h5>Chamados</h5>

        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Assunto</th>
                    <th>Status</th>
                    <th style="width: 120px;"></th>
                </tr>
            </thead>

            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>

                    <td>
                        <strong><?= $row['assunto'] ?></strong>
                    </td>

                    <td>
                        <span class="badge status-<?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>

                    <td>
                        <a href="chamado_ver.php?id=<?= $row['id'] ?>" 
                           class="btn btn-sm btn-primary">
                            Abrir
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>

        </table>
    </div>

</div>

<script>

// ROSCA
new Chart(document.getElementById('graficoStatus'), {
    type: 'doughnut',
    data: {
        labels: ['Abertos','Pendentes','Resolvidos'],
        datasets: [{
            data: [<?= $abertos ?>, <?= $pendentes ?>, <?= $resolvidos ?>]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// LINHA
new Chart(document.getElementById('graficoLinha'), {
    type: 'line',
    data: {
        labels: <?= json_encode($datas) ?>,
        datasets: [{
            label: 'Chamados',
            data: <?= json_encode($valores) ?>,
            tension: 0.3
        }]
    }
});

</script>

</body>
</html>