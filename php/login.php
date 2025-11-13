<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "conexao.php";

$email = trim($_POST['email'] ?? '');
$senhaCriptografada = trim($_POST['senha'] ?? '');
$chaveCriptografada = trim($_POST['chave'] ?? '');

if (empty($email) || empty($senhaCriptografada) || empty($chaveCriptografada)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Campos incompletos.']);
    exit;
}

// Busca o usuário
$stmt = $conexao->prepare("SELECT id_usuario, nome, senha, chave FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail não encontrado.']);
    exit;
}

$usuario = $result->fetch_assoc();
$stmt->close();


$privateKey = <<<RSA
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC8gpSHQyOhwFwW
E1kyHDnYbKx5TgR07nKn0wI0BybIBG58zhRkuyPaqbLkjhv63rraXd0liPTspt2d
EnDvgth8j7ToRPutE15GE5V8VDxK8QlluGKADsTx10OdkBtNLhRuNzovTziYoW1w
eCwqGT4dAO67e8LnR0VznuX/zB7el/XdupMpSwy+1AWGjVN4/8Mn+dSUynRdtxa+
s5lO14VAziZhQDAG8lZ9hXeZmUvh7uQWVNxvS0oAF5RSx495SGNpJSQA3gjVnJQC
BeXuVoCTu8l6++LTdFoUvgPoQpEWfnNT54xbJvXBfWlMNu4DCoC2k+e6tjHFpEM2
NDszNTUpAgMBAAECggEABjblz5m6hHwTbRThNWIx0/0tKGiWhAZSWE7//RgZQrHG
tK2UhYeX1H8QqzE2IeySy10Zy8F6lYygtKtldrfhDCYuqBOSGnAPhQ7jODCMtknA
7g4IjUEvY+udSoX0KHSy7rk6XVHmwz1bHsCPcCnZ3tnnhN29HKPAWeHsoiSoBd19
B/5Pi0GfezYkf4AlASi/YuiwrlO0vWhGt6dLYMgk+KipSaUKIef2v3/2RWiC0e1X
kVMdnUwx1ThZtMyi3L4o3P1Dc3GKzddabh7JuWbVTuuHo6mgKjaOytn7pFqMrzU3
IwiJKVt5m6BGkWsLusKBFC1GPm8b99v8dKzUgarNqQKBgQD0X/mJqEnnWMHngYg7
8+QVwTmUZ8ypubCZuYqFxmrhWOitZsVoTnHriypVR+DdL5lwjY4aAwu7ZrlIIA+c
eSnLeG2frAHQrSST4Et1eMBXOylZdiQbkW6Vi8a0lorqepsvBi2fCpB1tD6xzB2t
khg+w00KfFQDHueAcffDRDhwJQKBgQDFekb8o8MHUlPLtekCRijTEB/7F+nRYZvz
OS04QZBlAqje510f4m+iDKLZKpW/KM5A6ziEo9fcorUZ4YL27M2IdaYbFj0Odokm
Sf1JZN9dNw7nymU3yMeeVRf0dLV0R2pddBjQtjywJar+oZ49j6rEcmA7EF38xTHp
Qc2KECDPtQKBgD6KIU59Zwxxxo38tGTDceevX5D/T31QLEXrGexKbyfknQdjebnL
+ZOSNe9FSeLRP69ySp+Vj/cRvvEaY2RkDbIoy8VFO+GvreC6UhVqJa8tUNO4TF1R
xr822k91B3AimHxcVGiTR40X58OQvTeiX9oQGTNJxiZWTmCsyf+LAfaRAoGBAJfA
gXB/f83OjCpNHkPrGlAnYgwYskt+kNL1qzRB3e5hftfcP4nS/iovP61WtkBx4R2u
JH55UdZxCm5bB8Ms8jzc+8pw6naEzJwp24RdCfCd8MxakjGuDwgzJ/dpAE/c2XX7
U15/zF8ocyC9ndZ84qOXqS96Ql+OQe6X7JUeE7jJAoGAQGYtloWNUScPbn8jC8og
glEYQUZ2jOmFPIFAM4qV6TUN68h9vgapyZWyw2fHPqr5I+iFqm87G+5b02dvLIBu
c2nGdshEZoUUkU1JcKX6AygBZP7cPV1+5HDGrwGPvvKT+UKUnw2Msi7/qBQNZF+n
0Yu8rNTLiSl8c8Cn8n+E+rs=
-----END PRIVATE KEY-----
RSA;

$aesKeyBanco = '';
if (!openssl_private_decrypt(base64_decode($usuario['chave']), $aesKeyBanco, $privateKey)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Falha ao descriptografar AES do banco']);
    exit;
}


function decryptAES_CryptoJS($cipherTextBase64, $key) {
    $cipherData = base64_decode($cipherTextBase64);
    if (substr($cipherData, 0, 8) !== "Salted__") return false;

    $salt = substr($cipherData, 8, 8);
    $ct = substr($cipherData, 16);
    $rounds = 3;
    $data00 = $key . $salt;
    $md5 = [md5($data00, true)];
    $result = $md5[0];
    for ($i = 1; $i < $rounds; $i++) {
        $md5[$i] = md5($md5[$i - 1] . $data00, true);
        $result .= $md5[$i];
    }
    $key_dec = substr($result, 0, 32);
    $iv_dec  = substr($result, 32, 16);
    return openssl_decrypt($ct, 'aes-256-cbc', $key_dec, OPENSSL_RAW_DATA, $iv_dec);
}

$senhaBanco = decryptAES_CryptoJS($usuario['senha'], $aesKeyBanco);
if ($senhaBanco === false) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao descriptografar senha no banco']);
    exit;
}


$aesKeyLogin = '';
if (!openssl_private_decrypt(base64_decode($chaveCriptografada), $aesKeyLogin, $privateKey)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Falha ao descriptografar chave RSA enviada']);
    exit;
}

$senhaDigitada = decryptAES_CryptoJS($senhaCriptografada, $aesKeyLogin);

if ($senhaBanco === $senhaDigitada) {
    session_start();
    $_SESSION['usuario_id'] = $usuario['id_usuario'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['logado'] = true;

    echo json_encode(['sucesso' => true, 'mensagem' => 'Login realizado com sucesso!']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Senha incorreta!']);
}
?>
