<?php
// Inicia a sessão
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
$nome = $_POST['nome'] ?? '';
$bio = $_POST['bio'] ?? null;

if ($senha !== $confirmar) {
    $_SESSION['erro_login'] = "Senhas não coincidem.";
    header('Location: cadastro.php');
    exit;
}


$avatarPath = null;
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (in_array($_FILES['avatar']['type'], $allowedTypes)) {
        if ($_FILES['avatar']['size'] < 2 * 1024 * 1024) { // max 2MB
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('avatar_') . '.' . $ext;
            $uploadDir = __DIR__ . '/uploads/avatars/';
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename)) {
                $avatarPath = 'uploads/avatars/' . $filename;
            }
        } else {
            $_SESSION['erro_login'] = "Imagem muito grande (máx 2MB)";
            header('Location: cadastro.php');
            exit;
        }
    }
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO login (email, usuario, senha, nome, bio, avatar) VALUES (:email, :usuario, :senha, :nome, :bio, :avatar)"
);

$stmt->bindParam(':email', $email);
$stmt->bindParam(':usuario', $usuario);
$stmt->bindParam(':senha', $senhaHash);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':bio', $bio);
$stmt->bindParam(':avatar', $avatarPath);

try {
    // Executa a inserção do novo usuário no banco de dados
    $stmt->execute();
} catch (PDOException $e) {
    $_SESSION['erro_login'] = "Erro: e-mail ou usuário possivelmente já em uso.";
    header('Location: cadastro.php');
    exit;
}

// Redireciona o usuário para a página de login após cadastro bem-sucedido
header('Location: login.php');
exit;
