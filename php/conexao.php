<?php
require_once 'config.php';

// Tenta conectar
$conexao = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Se falhar, retorna JSON e encerra com código de erro HTTP
if (!$conexao) {
    http_response_code(500); // código 500 = erro interno
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Falha na conexão com o banco de dados. Verifique o arquivo config.php.'
    ]);
    exit(); // importante: encerra sem gerar HTML
}
?>
