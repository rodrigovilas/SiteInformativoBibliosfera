<?php
session_start();
include __DIR__ . "/database.php";

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: comunidade.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_resenha = $_GET['id'];

try {
    // Busca a resenha para conferir se o usuário é o dono
    $stmt_check = $conn->prepare("SELECT id_usuario FROM resenha WHERE id_resenha = :id_res");
    $stmt_check->execute(['id_res' => $id_resenha]);
    $resenha = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($resenha && $resenha['id_usuario'] == $id_usuario) {
        $stmt_del = $conn->prepare("DELETE FROM resenha WHERE id_resenha = :id_res");
        $stmt_del->execute(['id_res' => $id_resenha]);
        $_SESSION['sucesso_comunidade'] = "Comentário excluído com sucesso!";
    } else {
        $_SESSION['erro_comunidade'] = "Você não tem permissão para excluir este comentário.";
    }

} catch (PDOException $e) {
    $_SESSION['erro_comunidade'] = "Erro ao excluir: " . $e->getMessage();
}

header("Location: comunidade.php");
exit;
?>
