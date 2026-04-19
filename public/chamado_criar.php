<?php
session_start();
require_once('../config/database.php');
require_once('../config/auth.php');

if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $assunto = $_POST['assunto'];
    $descricao = $_POST['descricao'];
    $usuario_id = $_SESSION['usuario']['id'];

    // Criar chamado
    $stmt = $conn->prepare("INSERT INTO chamados (assunto, descricao, solicitante_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $assunto, $descricao, $usuario_id);
    $stmt->execute();

    $chamado_id = $conn->insert_id;

    // Upload de arquivo
    if (!empty($_FILES['arquivo']['name'])) {

        $nome = $_FILES['arquivo']['name'];
        $tmp = $_FILES['arquivo']['tmp_name'];

        $destino = "uploads/" . time() . "_" . $nome;

        move_uploaded_file($tmp, $destino);

        $stmt = $conn->prepare("INSERT INTO anexos (chamado_id, nome_arquivo, caminho) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $chamado_id, $nome, $destino);
        $stmt->execute();
    }

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AlicerceDesk - Novo Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="sidebar">
    <h4>AlicerceDesk</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="chamado_criar.php" class="active">Novo Chamado</a>
    <a href="logout.php">Sair</a>
</div>

<div class="content">

    <div class="topbar">Novo Chamado</div>

    <div class="card p-3 form-card">

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Assunto</label>
                <input type="text" name="assunto" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label>Anexo</label>
                <input type="file" name="arquivo" class="form-control">
            </div>

            <button class="btn btn-primary">Criar Chamado</button>

        </form>

    </div>

</div>

</body>
</html>