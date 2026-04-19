<?php
require_once('../config/database.php');

$chamado_id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT mc.*, u.nome, a.caminho, a.nome_arquivo
    FROM mensagens_chamado mc
    JOIN usuarios u ON mc.remetente_id = u.id
    LEFT JOIN anexos a ON a.mensagem_id = mc.id
    WHERE mc.chamado_id = ?
    ORDER BY mc.id ASC
");
$stmt->bind_param("i", $chamado_id);
$stmt->execute();

$result = $stmt->get_result();

$dados = [];

while($row = $result->fetch_assoc()) {
    $dados[] = $row;
}

echo json_encode($dados);