<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

$nome = trim($_POST["nome"] ?? '');
$email = trim($_POST["email"] ?? '');
$data = trim($_POST["data"] ?? '');
$telefone = trim($_POST["telefone"] ?? '');
$senhaCriptografada = trim($_POST["senha"] ?? '');
$chaveEncriptada = trim($_POST["chave"] ?? '');

if (empty($nome) || empty($email) || empty($data) || empty($senhaCriptografada) || empty($chaveEncriptada)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Todos os campos obrigatórios devem ser preenchidos!']);
    exit();
}

include "conexao.php";


$stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, data_nascimento, telefone, senha, chave) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na preparação da query: ' . $conexao->error]);
    exit();
}


$stmt->bind_param("ssssss", $nome, $email, $data, $telefone, $senhaCriptografada, $chaveEncriptada);

if ($stmt->execute()) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário cadastrado com sucesso!']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco de dados: ' . $stmt->error]);
}

$stmt->close();
$conexao->close();
?>
