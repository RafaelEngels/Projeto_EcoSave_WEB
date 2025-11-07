
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
  fetch('../php/mostrar_denuncias.php')
    .then(response => response.text())
    .then(dados => {
      var denuncias = JSON.parse(dados);
      var conteudo = "";

      if (denuncias.length > 0) {
        for (var i = 0; i < denuncias.length; i++) {
          conteudo += "<div class='denuncia'>";
          conteudo += "<p><strong>Data:</strong> " + denuncias[i].data_denuncia + "</p>";
          conteudo += "<p><strong>Local:</strong> " + denuncias[i].loca_denuncia + "</p>";
          conteudo += "<p><strong>Descrição:</strong> " + denuncias[i].desc_denuncia + "</p>";
          conteudo += "<p><strong>Nº de animais:</strong> " + denuncias[i].num_animais + "</p>";
          conteudo += "<p><strong>Espécie:</strong> " + denuncias[i].especie + "</p>";
          conteudo += "<hr></div>";
        }
      } else {
        conteudo = "<p>Nenhuma denúncia encontrada.</p>";
      }

      document.getElementById('mostrar').innerHTML = conteudo;
    })
    .catch(error => {
      console.error('Erro ao carregar denúncias:', error);
      document.getElementById('mostrar').innerHTML = '<p>Erro ao carregar denúncias.</p>';
    });
}


