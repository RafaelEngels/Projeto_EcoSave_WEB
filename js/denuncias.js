
function enviar() {
    var formDenuncia = document.getElementById("form-denuncia");
    var dados = new FormData(formDenuncia);

    fetch("../php/realizar_denuncias.php", {
        method: "POST",
        body: dados
    })
    .then(response => response.json())
    .then(data => {
        // Mostra a mensagem do PHP
        alert(data.mensagem);

        if (data.sucesso) {
            formDenuncia.reset(); // limpa todos os inputs
        }
    })
    .catch(error => {
        console.error("Erro de conexão ou JSON:", error);
        alert("Ocorreu um erro inesperado ao conectar ao servidor.");
    });
}

function mostrar_denuncias() {
  fetch('caminho/mostrar_denuncias.php')  
    .then(response => response.text())    
    .then(html => {
      document.querySelector('.mostrar').innerHTML = html;  
    })
    .catch(error => {
      console.error('Erro ao carregar denúncias:', error);
      document.querySelector('.mostrar').innerHTML = '<p>Erro ao carregar denúncias.</p>';
    });
}

