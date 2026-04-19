<?php
$perfil = $_SESSION['usuario']['perfil'];

function menuAtivo($pagina) {
    return strpos($_SERVER['PHP_SELF'], $pagina) !== false ? 'active' : '';
}
?>

<div class="sidebar">
    <h4>AlicerceDesk</h4>

    <a href="dashboard.php" class="<?= menuAtivo('dashboard.php') ?>">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <a href="chamado_criar.php" class="<?= menuAtivo('chamado_criar.php') ?>">
        <i class="bi bi-plus-circle"></i>
        Novo Chamado
    </a>

    <?php if ($perfil != 'cliente'): ?>

        <a href="#" class="<?= menuAtivo('relatorios.php') ?>">
            <i class="bi bi-bar-chart"></i>
            Relatórios
        </a>

        <a href="#" class="<?= menuAtivo('usuarios.php') ?>">
            <i class="bi bi-people"></i>
            Usuários
        </a>

    <?php endif; ?>

    <a href="logout.php">
        <i class="bi bi-box-arrow-right"></i>
        Sair
    </a>
</div>