<?php
session_start();
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['usuario'] = $resultado->fetch_assoc();
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "Email ou senha inválidos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AlicerceDesk - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-page">

<div class="login-card">

    <h3 class="login-title">AlicerceDesk</h3>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger text-center">
            <?= $erro ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-dark w-100">
            Entrar
        </button>

    </form>

    <div class="login-footer">
        AlicerceDesk © <?= date('Y') ?>
    </div>

</div>

</body>
</html>