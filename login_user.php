<?php
// Arquivo de autenticação do usuário Processa o login e valida as credenciais

session_start();
include __DIR__ . "/database.php";

// Verifica se os dados de email e senha foram enviados pelo formulário
if (!isset($_POST['email'], $_POST['senha'])) {
    header('Location: login.php');
    exit;
}

// Recebe os dados do formulário de login
$email = $_POST['email'];
$senha = $_POST['senha'];

// Busca o usuário no banco de dados pelo email
$stmt = $conn->prepare(
    "SELECT usuario, senha FROM login WHERE email = :email"
);
$stmt->bindParam(':email', $email);
$stmt->execute();

// Obtém os dados do usuário encontrado
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe e se a senha está correta
if (!$user || $senha !== $user['senha']) {
    $_SESSION['erro_login'] = "E-mail ou senha inválidos";
    header('Location: login.php');
    exit;
}

// Se as credenciais estão corretas, armazena os dados na sessão
$_SESSION['id_user'] = $user['id_user'];
$_SESSION['usuario'] = $user['usuario'];

// Redireciona para a página inicial do usuário autenticado
header('Location: home.html');
exit;
?>