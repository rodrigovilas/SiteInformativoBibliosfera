<?php
session_start();
include __DIR__ . "/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome = $_POST['nome'] ?? '';
$bio = $_POST['bio'] ?? '';


$avatarPath = null;
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (in_array($_FILES['avatar']['type'], $allowedTypes)) {
        if ($_FILES['avatar']['size'] < 2 * 1024 * 1024) { // Limite aprox 2MB
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('avatar_') . '.' . $ext;
            $uploadDir = __DIR__ . '/uploads/avatars/';
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename)) {
                $avatarPath = 'uploads/avatars/' . $filename;
            }
        }
    }
}

if ($avatarPath !== null) {
    $stmt = $conn->prepare("UPDATE login SET nome = :nome, bio = :bio, avatar = :avatar WHERE id_usuario = :id");
    $stmt->bindParam(':avatar', $avatarPath);
} else {
    // Se nenhum arquivo válido subiu, mantem o que já estava.
    $stmt = $conn->prepare("UPDATE login SET nome = :nome, bio = :bio WHERE id_usuario = :id");
}

$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':bio', $bio);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();

header('Location: perfil.php');
exit;
