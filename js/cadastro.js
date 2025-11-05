function cadastro(){
    var form_cadastro = document.getElementById("cadastro");
    var dados = new FormData(form_cadastro);


    fetch("php/cadastro.php", {
        method:"POST",
        body:dados
    })
    .then(response => response.json()) // Recebe a resposta e converte para JSON
    .then(data => {
        // Verifica o status 'sucesso' retornado pelo PHP
        alert(data.mensagem); // Mostra o ALERTA para o cliente

        if (data.sucesso) {
            // Se for sucesso, redireciona para a página inicial
            window.location.href = '../paginas/pagina_inicial.html'; 
        }
        // Se for erro, o alert já mostrou a mensagem, e o usuário permanece no formulário.
    })
    .catch(error => {
        // Trata erros 
        console.error('Erro de conexão ou JSON:', error);
        alert('Ocorreu um erro inesperado ao conectar ao servidor.');
    });
}