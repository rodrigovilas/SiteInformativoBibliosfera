<?php
// Inicia a sessão
session_start();
?>

<?php
// Inclui o arquivo de configuração do banco de dados
include __DIR__ . "/database.php";

// Verifica se todos os campos obrigatórios do formulário foram enviados Se não redireciona para a página de cadastro
if (!isset($_POST['email'], $_POST['usuario'], $_POST['senha'], $_POST['confirmarsenha'])) {
    header('Location: cadastro.php');
    exit;
}

// Recebe os dados do formulário de cadastro
$email = $_POST['email'];
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$confirmar = $_POST['confirmarsenha'];

// Valida se as duas senhas digitadas são iguais Se não forem exibe mensagem de erro e redireciona
if ($senha !== $confirmar) {
    $_SESSION['erro_login'] = "E-mail ou senha inválidos";
    header('Location: cadastro.php');
    exit;
}



$stmt = $conn->prepare(
    "INSERT INTO login (email, usuario, senha) VALUES (:email, :usuario, :senha)"
);


$stmt->bindParam(':email', $email);
$stmt->bindParam(':usuario', $usuario);
$stmt->bindParam(':senha', $senha);

// Executa a inserção do novo usuário no banco de dados
$stmt->execute();

// Redireciona o usuário para a página de login após cadastro bem-sucedido
header('Location: login.php');
exit;
