<?php
// Define o tipo de conteúdo como JSON
header('Content-Type: application/json; charset=utf-8');


$data_denuncia  = $_POST["data_denuncia"] ?? '';
$local_denuncia = $_POST["local_denuncia"] ?? '';
$desc_denuncia  = $_POST["desc_denuncia"] ?? '';
$num_animais    = $_POST["num_animais"] ?? '';
$especie        = $_POST["especie"] ?? '';

// tratamento de erro pra ver se algum campo ta vazio
if (empty($data_denuncia) || empty($local_denuncia) || empty($desc_denuncia) || empty($num_animais) || empty($especie)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ERRO: Todos os campos obrigatórios devem ser preenchidos!']);
    exit();
}


include "conexao.php";

// tratamento de erro pra escape de dados
$data_denuncia  = mysqli_real_escape_string($conexao, $data_denuncia);
$local_denuncia = mysqli_real_escape_string($conexao, $local_denuncia);
$desc_denuncia  = mysqli_real_escape_string($conexao, $desc_denuncia);
$num_animais    = (int) $num_animais;
$especie        = mysqli_real_escape_string($conexao, $especie);

// Monta e executa a query
$query = "INSERT INTO denuncias (data_denuncia, local_denuncia, desc_denuncia, num_animais, especie) VALUES ('$data_denuncia', '$local_denuncia', '$desc_denuncia', $num_animais, '$especie')";

$resultado = mysqli_query($conexao, $query);

// Retorno JSON
if ($resultado) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Denúncia cadastrada com sucesso!']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ERRO no banco de dados: ' . mysqli_error($conexao)]);
}
?>
