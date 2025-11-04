<?php
    
    // 1. CHAMA o arquivo de configuração local
    require_once 'config.php';
    
    // 2. Conecta usando as constantes
    $conexao = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // 3. Verifica se a conexão falhou e exibe um erro útil
    if (!$conexao) {
        die("ERRO: Falha na conexão com o banco de dados. Verifique o seu 'config.php' local.");
    }

?>