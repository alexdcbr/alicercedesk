<?php
session_start();
require_once('../config/database.php');
require_once('../config/auth.php');

if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

// 🔹 RESOLVER CHAMADO (SLA)
if (isset($_GET['resolver']) && isAgenteOuAdmin()) {
    $stmt = $conn->prepare("
        UPDATE chamados 
        SET status='resolvido', resolvido_em = NOW() 
        WHERE id=?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: chamado_ver.php?id=$id");
    exit;
}

// 🔹 NOVA MENSAGEM + ANEXO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $mensagem = $_POST['mensagem'];
    $uid = $_SESSION['usuario']['id'];

    // 🔥 REGISTRA PRIMEIRA RESPOSTA (FRT)
    if (isAgenteOuAdmin()) {
        $stmt = $conn->prepare("
            UPDATE chamados 
            SET primeira_resposta_em = NOW() 
            WHERE id = ? AND primeira_resposta_em IS NULL
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // inserir mensagem
    $stmt = $conn->prepare("
        INSERT INTO mensagens_chamado (chamado_id, remetente_id, mensagem) 
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iis", $id, $uid, $mensagem);
    $stmt->execute();

    $mensagem_id = $conn->insert_id;

    // upload
    if (!empty($_FILES['arquivo']['name'])) {

        $nome = $_FILES['arquivo']['name'];
        $tmp = $_FILES['arquivo']['tmp_name'];

        $destino = "uploads/" . time() . "_" . $nome;
        move_uploaded_file($tmp, $destino);

        $stmt = $conn->prepare("
            INSERT INTO anexos (chamado_id, mensagem_id, nome_arquivo, caminho) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iiss", $id, $mensagem_id, $nome, $destino);
        $stmt->execute();
    }

    header("Location: chamado_ver.php?id=$id");
    exit;
}

// 🔹 FUNÇÃO IMAGEM
function ehImagem($arquivo) {
    $ext = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg','jpeg','png','gif','webp']);
}

// 🔹 BUSCAR CHAMADO
$stmt = $conn->prepare("
    SELECT c.*, u.nome as solicitante 
    FROM chamados c
    JOIN usuarios u ON c.solicitante_id = u.id
    WHERE c.id=?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$chamado = $stmt->get_result()->fetch_assoc();

// 🔐 PERMISSÃO
if (isCliente() && $chamado['solicitante_id'] != $_SESSION['usuario']['id']) {
    die("Acesso negado.");
}

// 🔹 ANEXOS INICIAIS
$stmt = $conn->prepare("
    SELECT * FROM anexos 
    WHERE chamado_id=? AND mensagem_id IS NULL
");
$stmt->bind_param("i", $id);
$stmt->execute();
$anexos_iniciais = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>AlicerceDesk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="sidebar">
    <h4>AlicerceDesk</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="chamado_criar.php">Novo Chamado</a>
    <a href="logout.php">Sair</a>
</div>

<div class="content">

    <!-- TOPO -->
    <div class="topbar">
        <strong>#<?= $id ?> - <?= $chamado['assunto'] ?></strong><br>
        <small>
            Solicitante: <?= $chamado['solicitante'] ?> |
            Status: <?= $chamado['status'] ?>
        </small>
    </div>

    <!-- BOTÃO RESOLVER -->
    <?php if (isAgenteOuAdmin() && $chamado['status'] != 'resolvido'): ?>
        <a href="?id=<?= $id ?>&resolver=1" class="btn btn-success mb-3">
            Marcar como Resolvido
        </a>
    <?php endif; ?>

    <!-- ABERTURA -->
    <div class="card p-3 mb-3">
        <div class="chat-container">

            <div class="chat-bubble chat-other">
                <div class="chat-autor"><?= $chamado['solicitante'] ?> (abertura)</div>

                <?= nl2br($chamado['descricao']) ?>

                <?php while($a = $anexos_iniciais->fetch_assoc()): ?>

                    <?php if (ehImagem($a['caminho'])): ?>
                        <img src="<?= $a['caminho'] ?>" class="chat-image" onclick="abrirImagem(this.src)">
                    <?php else: ?>
                        <div class="mt-2">
                            📎 <a href="<?= $a['caminho'] ?>" target="_blank">
                                <?= $a['nome_arquivo'] ?>
                            </a>
                        </div>
                    <?php endif; ?>

                <?php endwhile; ?>

            </div>

        </div>
    </div>

    <!-- CHAT DINÂMICO -->
    <div class="card p-3 mb-3">
        <div id="chat-box" class="chat-container"></div>
    </div>

    <!-- INPUT -->
    <div class="card p-3">
        <form method="POST" enctype="multipart/form-data" class="chat-input">

            <textarea name="mensagem" class="form-control" rows="2" required></textarea>

            <input type="file" name="arquivo" class="form-control">

            <button class="btn btn-primary">Enviar</button>

        </form>
    </div>

</div>

<!-- MODAL -->
<div id="imageModal" class="image-modal" onclick="fecharImagem()">
    <span class="image-modal-close">&times;</span>
    <img id="modalImg">
</div>

<script>

const usuarioId = <?= $_SESSION['usuario']['id'] ?>;
const chamadoId = <?= $id ?>;

// 🔹 DETECTAR IMAGEM
function ehImagem(nome) {
    return nome && nome.match(/\.(jpg|jpeg|png|gif|webp)$/i);
}

// 🔹 RENDER
function renderMensagem(m) {

    const minha = m.remetente_id == usuarioId;

    let html = `
        <div class="chat-bubble ${minha ? 'chat-me' : 'chat-other'}">
            <div class="chat-autor">${m.nome}</div>
            ${m.mensagem}
    `;

    if (m.caminho) {
        if (ehImagem(m.caminho)) {
            html += `<img src="${m.caminho}" class="chat-image" onclick="abrirImagem(this.src)">`;
        } else {
            html += `<div class="mt-2">📎 <a href="${m.caminho}" target="_blank">${m.nome_arquivo}</a></div>`;
        }
    }

    html += `</div>`;
    return html;
}

// 🔹 CARREGAR MENSAGENS
function carregarMensagens() {
    fetch(`api_mensagens.php?id=${chamadoId}`)
    .then(res => res.json())
    .then(data => {

        let chat = document.getElementById("chat-box");
        chat.innerHTML = "";

        data.forEach(m => {
            chat.innerHTML += renderMensagem(m);
        });

        chat.scrollTop = chat.scrollHeight;
    });
}

// 🔁 AUTO UPDATE
setInterval(carregarMensagens, 2000);
carregarMensagens();

// 🔹 MODAL
function abrirImagem(src) {
    document.getElementById("imageModal").style.display = "block";
    document.getElementById("modalImg").src = src;
}

function fecharImagem() {
    document.getElementById("imageModal").style.display = "none";
}

</script>

</body>
</html>