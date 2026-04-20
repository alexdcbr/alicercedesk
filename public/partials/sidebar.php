<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$perfil = $_SESSION['usuario']['perfil'] ?? '';

// Função para marcar item ativo automaticamente
function menuAtivo($pagina) {
    return strpos($_SERVER['PHP_SELF'], $pagina) !== false ? 'active' : '';
}
?>

<div class="sidebar">

    <!-- LOGO -->
    <div class="sidebar-logo">
        <h4>AlicerceDesk</h4>
    </div>

    <!-- MENU -->
    <nav class="sidebar-menu">

        <a href="dashboard.php" class="<?= menuAtivo('dashboard.php') ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="chamado_criar.php" class="<?= menuAtivo('chamado_criar.php') ?>">
            <i class="bi bi-plus-circle"></i>
            <span>Novo Chamado</span>
        </a>

        <!-- SOMENTE ADMIN / AGENTE -->
        <?php if ($perfil != 'cliente'): ?>

            <a href="#" class="<?= menuAtivo('relatorios.php') ?>">
                <i class="bi bi-bar-chart"></i>
                <span>Relatórios</span>
            </a>

            <a href="#" class="<?= menuAtivo('usuarios.php') ?>">
                <i class="bi bi-people"></i>
                <span>Usuários</span>
            </a>

        <?php endif; ?>

        <!-- LOGOUT -->
        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sair</span>
        </a>

    </nav>

</div>