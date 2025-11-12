document.addEventListener("DOMContentLoaded", function () {
  const botao = document.querySelector(".botao");
  if (!botao) return;

  botao.addEventListener("click", realizarLogin);

  function realizarLogin() {
    const form = document.getElementById("formLogin");
    const dados = new FormData(form);

    const email = dados.get("email");
    const senha = dados.get("senha");

    if (!email || !senha) {
      alert("Preencha e-mail e senha!");
      return;
    }

    // --- Criptografia igual ao cadastro ---
    var aesKey = CryptoJS.lib.WordArray.random(32).toString(CryptoJS.enc.Base64);
    var senhaCriptografada = CryptoJS.AES.encrypt(senha, aesKey).toString();

    // Criptografa AES key com RSA (chave pública)
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

    // Substitui os valores do form
    dados.set("senha", senhaCriptografada);
    dados.set("chave", chaveCriptografada);

    // --- Envia pro PHP ---
    fetch("../php/login.php", {
      method: "POST",
      body: dados,
    })
      .then(async (response) => {
        const texto = await response.text();
        try {
          return JSON.parse(texto);
        } catch {
          throw new Error("Resposta inválida do servidor:\n" + texto);
        }
      })
      .then((data) => {
        alert(data.mensagem);
        if (data.sucesso) {
          window.location.href = "../index.html";
        }
      })
      .catch((error) => {
        console.error("Erro de conexão ou JSON:", error);
        alert("Erro de conexão: " + error.message);
      });
  }
});
