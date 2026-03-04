<?php
session_start();
?>

<?php
include __DIR__ . "/database.php";

if (!isset($_POST['email'], $_POST['usuario'], $_POST['senha'], $_POST['confirmarsenha'])) {
    header('Location: cadastro.php');
    exit;
}

$email = $_POST['email'];
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$confirmar = $_POST['confirmarsenha'];

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

$stmt->execute();

header('Location: login.php');
exit;
