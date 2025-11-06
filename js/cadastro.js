function cadastro(){
    var form_cadastro = document.getElementById("cadastro");
    var dados = new FormData(form_cadastro);

    fetch("../php/cadastro.php", {
        method:"POST",
        body:dados
    })
    .then(response => response.json()) 
    .then(data => {

        alert(data.mensagem); 

        if (data.sucesso) {

            window.location.href = '../paginas/pagina_inicial.html'; 
        }
    })
    .catch(error => {
        // Trata erros de rede ou de parsing do JSON
        console.error('Erro de conexão ou JSON:', error);
        alert('Ocorreu um erro inesperado ao conectar ao servidor.');
    });
}