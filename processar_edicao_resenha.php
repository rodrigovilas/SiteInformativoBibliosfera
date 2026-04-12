<?php
session_start();
include __DIR__ . "/database.php";

if (!isset($_SESSION['id_usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: comunidade.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_resenha = $_POST['id_resenha'];
$nova_nota = $_POST['nota'];
$nova_resenha = htmlspecialchars($_POST['resenha'], ENT_QUOTES, 'UTF-8');

try {
    // 1. Verifica se o usuário é o dono da resenha
    $stmt_check = $conn->prepare("SELECT id_usuario FROM resenha WHERE id_resenha = :id_res");
    $stmt_check->execute(['id_res' => $id_resenha]);
    $resenha = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($resenha && $resenha['id_usuario'] == $id_usuario) {
        // 2. Atualiza a resenha
        $stmt_upd = $conn->prepare("UPDATE resenha SET nota = :nota, resenha = :msg WHERE id_resenha = :id_res");
        $stmt_upd->execute([
            ':nota' => $nova_nota,
            ':msg' => $nova_resenha,
            ':id_res' => $id_resenha
        ]);
        $_SESSION['sucesso_comunidade'] = "Comentário atualizado com sucesso!";
    } else {
        $_SESSION['erro_comunidade'] = "Você não tem permissão para editar este comentário.";
    }

} catch (PDOException $e) {
    $_SESSION['erro_comunidade'] = "Erro ao atualizar: " . $e->getMessage();
}

header("Location: comunidade.php");
exit;
?>
