<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

$email = trim($_POST["email"] ?? '');
$senhaCriptografada = trim($_POST["senha"] ?? '');
$chaveEncriptada = trim($_POST["chave"] ?? '');

if (empty($email) || empty($senhaCriptografada) || empty($chaveEncriptada)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail e senha são obrigatórios!']);
    exit();
}

include "conexao.php";

// Buscar usuário pelo email
$stmt = $conexao->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
if (!$stmt) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na preparação da query: ' . $conexao->error]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail não cadastrado!']);
    $stmt->close();
    exit();
}

$usuario = $result->fetch_assoc();
$stmt->close();

// Verificar se a senha criptografada corresponde
// IMPORTANTE: Como estamos usando criptografia assimétrica, a comparação é feita
// com as versões criptografadas, já que não temos a chave privada para descriptografar
if ($senhaCriptografada === $usuario['senha']) {
    // Login bem-sucedido
    session_start();
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $email;
    $_SESSION['logado'] = true;
    
    echo json_encode([
        'sucesso' => true, 
        'mensagem' => 'Login realizado com sucesso!',
        'usuario' => [
            'id' => $usuario['id'],
            'nome' => $usuario['nome']
        ]
    ]);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Senha incorreta!']);
}
?>
