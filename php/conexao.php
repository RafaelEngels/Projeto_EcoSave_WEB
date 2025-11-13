<?php
require_once 'config.php';


$conexao = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);


if (!$conexao) {
    http_response_code(500); 
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Falha na conexão com o banco de dados. Verifique o arquivo config.php.'
    ]);
    exit(); 
}
?>
