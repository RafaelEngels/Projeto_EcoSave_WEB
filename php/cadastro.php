<?php
    // Define o tipo de conteúdo como JSON para o JavaScript
    header('Content-Type: application/json; charset=utf-8'); 
    
    // Função utilitária para retornar erro em JSON
    function retornar_erro($mensagem) {
        echo json_encode(['sucesso' => false, 'mensagem' => $mensagem]);
        exit();
    }


  // COLETAR E PREVENIR ERROS

    $nome = $_POST["nome"] ?? '';
    $email= $_POST["email"] ?? '';
    $data = $_POST["data"] ?? '';
    $telefone = $_POST["telefone"] ?? '';
    $senha = $_POST["senha"] ?? '';


    // VALIDAÇÃO DE CAMPOS NOT NULL

    if (empty($nome) || empty($email) || empty($data) || empty($senha)) {
        retornar_erro("ERRO: Todos os campos obrigatórios devem ser preenchidos!");
    }
    

    // CONEXÃO E INSERÇÃO NO BANCO DE DADOS

    
    include "conexao.php"; 

    $query = "INSERT INTO usuarios (nome, email, data_nascimento, telefone, senha) VALUES ('$nome','$email', '$data', '$telefone', '$senha')";
    
    $resultado = mysqli_query($conexao, $query);

    if ($resultado) {
        
        //  SUCESSO 
        echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário cadastrado com sucesso!']);
        
    } else {
        
        //  FALHA NA QUERY 
        $erro_mysql = mysqli_error($conexao);
        retornar_erro("ERRO no banco de dados: " . $erro_mysql);
    }
?>