// Certifique-se de que as bibliotecas CryptoJS e JSEncrypt estão carregadas no seu HTML antes deste script.

function cadastro() {
  var form_cadastro = document.getElementById("cadastro");
  var dados = new FormData(form_cadastro);

  // 1. Gerar chave AES aleatória (256 bits) em base64
  var aesKey = CryptoJS.lib.WordArray.random(32).toString(CryptoJS.enc.Base64);

  // 2. Criptografar a senha com AES usando a chave gerada
  var senha = dados.get('senha');
  var senhaCriptografada = CryptoJS.AES.encrypt(senha, aesKey).toString();

  // 3. Substituir senha no form por senha criptografada
  dados.set('senha', senhaCriptografada);

  // 4. Criptografar a chave AES com RSA usando a chave pública
  var rsa = new JSEncrypt();
  rsa.setPublicKey(`-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvIKUh0MjocBcFhNZMhw5
2GyseU4EdO5yp9MCNAcmyARufM4UZLsj2qmy5I4b+t662l3dJYj07KbdnRJw74LY
fI+06ET7rRNeRhOVfFQ8SvEJZbhigA7E8ddDnZAbTS4Ubjc6L084mKFtcHgsKhk+
HQDuu3vC50dFc57l/8we3pf13bqTKUsMvtQFho1TeP/DJ/nUlMp0XbcWvrOZTteF
QM4mYUAwBvJWfYV3mZlL4e7kFlTcb0tKABeUUsePeUhjaSUkAN4I1ZyUAgXl7laA
k7vJevvi03RaFL4D6EKRFn5zU+eMWyb1wX1pTDbuAwqAtpPnurYxxaRDNjQ7MzU1
KQIDAQAB
-----END PUBLIC KEY-----`);

  var chaveCriptografada = rsa.encrypt(aesKey);

  if (!chaveCriptografada) {
    alert('Erro ao criptografar chave AES com RSA.');
    return;
  }

  // 5. Adicionar a chave AES encriptada ao form (campo extra)
  dados.append('chave', chaveCriptografada);

  // 🔎 Teste opcional — verificar o que está sendo enviado
  /*
  for (var [k, v] of dados.entries()) {
    console.log(k, v);
  }
  */

  // 6. Enviar para o PHP
  fetch("../php/cadastro.php", {
    method: "POST",
    body: dados
  })
  .then(response => {
    if (!response.ok) {
      return response.text().then(text => {
        throw new Error("Erro HTTP: " + response.status + " - " + text);
      });
    }
    return response.json();
  })
  .then(data => {
    alert(data.mensagem);
    if (data.sucesso) {
      window.location.href = "login.html";
    }
  })
  .catch(error => {
    console.error("Erro de conexão ou JSON:", error);
    alert("Ocorreu um erro inesperado: " + error.message);
  });
}
