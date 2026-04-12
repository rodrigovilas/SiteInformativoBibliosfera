<?php
// Arquivo de autenticação do usuário 
// Processa o login e valida as credenciais. Tô amassando, slk

session_start();
include __DIR__ . "/database.php";

if (!isset($_POST['email'], $_POST['senha'])) {
    header('Location: login.php');
    exit;
}


$email = $_POST['email'];
$senha = $_POST['senha'];


$stmt = $conn->prepare(
    "SELECT id_usuario, usuario, senha FROM login WHERE email = :email"
);
$stmt->bindParam(':email', $email);
$stmt->execute();


$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($senha, $user['senha'])) {
    
    
    if ($user && $senha === $user['senha']) {
        
        $newHash = password_hash($senha, PASSWORD_DEFAULT);
        $up = $conn->prepare("UPDATE login SET senha = ? WHERE id_usuario = ?");
        $up->execute([$newHash, $user['id_usuario']]);
    } else {
        $_SESSION['erro_login'] = "E-mail ou senha inválidos";
        header('Location: login.php');
        exit;
    }
}


$_SESSION['id_usuario'] = $user['id_usuario'];
$_SESSION['usuario'] = $user['usuario'];

// Redireciona para a página inicial do usuário autenticado
header('Location: home.html');
exit;
?>