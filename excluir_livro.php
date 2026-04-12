<?php
session_start();
include __DIR__ . "/database.php";

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: leituras.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_livro = $_GET['id'];

try {
    $conn->beginTransaction();

    // 1. Remove do histórico de leitura (privado)
    $stmt_hist = $conn->prepare("DELETE FROM historico_leitura WHERE id_usuario = :id_u AND id_livro = :id_l");
    $stmt_hist->execute(['id_u' => $id_usuario, 'id_l' => $id_livro]);

    // 2. Remove da lista do usuário
    $stmt_lista = $conn->prepare("DELETE FROM listausuario WHERE id_usuario = :id_u AND id_livro = :id_l");
    $stmt_lista->execute(['id_u' => $id_usuario, 'id_l' => $id_livro]);

    // Opcional: Poderíamos remover o livro da tabela 'livro' se mais ninguém estiver lendo,
    // mas por segurança e para manter o histórico de resenhas públicas, vamos deixar o livro lá.
    // As resenhas públicas (tabela resenha) também permanecem para não quebrar a comunidade.

    $conn->commit();
    $_SESSION['sucesso_leitura'] = "Livro removido da sua lista!";
} catch (PDOException $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    $_SESSION['erro_leitura'] = "Erro ao excluir livro: " . $e->getMessage();
}

header("Location: leituras.php");
exit;
?>
