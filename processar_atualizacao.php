<?php
session_start();
include __DIR__ . "/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_livro = $_POST['id_livro'];
    $status = $_POST['status'];
    $pagina_atual = intval($_POST['pagina_atual'] ?? 0);
    $comentario_progresso = htmlspecialchars($_POST['comentario_progresso'] ?? '', ENT_QUOTES, 'UTF-8');
    $id_usuario = $_SESSION['id_usuario'];

    try {
        $conn->beginTransaction();

        // 1. Atualiza o status e a página atual do livro para o usuário
        $stmt_status = $conn->prepare("UPDATE listausuario SET progresso = :status, pagina_atual = :p_atual WHERE id_usuario = :id_u AND id_livro = :id_l");
        $stmt_status->execute([
            ':status' => $status,
            ':p_atual' => $pagina_atual,
            ':id_u' => $id_usuario,
            ':id_l' => $id_livro
        ]);

        // 2. Se houver um comentário de progresso, salva no histórico privado
        if (!empty($comentario_progresso)) {
            $stmt_hist = $conn->prepare("INSERT INTO historico_leitura (id_usuario, id_livro, pagina, comentario) VALUES (:id_u, :id_l, :pg, :msg)");
            $stmt_hist->execute([
                ':id_u' => $id_usuario,
                ':id_l' => $id_livro,
                ':pg' => $pagina_atual,
                ':msg' => $comentario_progresso
            ]);
        }

        // 3. Se o status for 'Terminado', salva a resenha no feed da comunidade
        if ($status === 'Terminado' && !empty($_POST['comentario'])) {
            $nota = isset($_POST['nota']) ? $_POST['nota'] : 10;
            $comentario_final = htmlspecialchars($_POST['comentario'], ENT_QUOTES, 'UTF-8');

            $stmt_resenha = $conn->prepare("INSERT INTO resenha (id_usuario, id_livro, nota, resenha) VALUES (:id_u, :id_l, :nota, :msg)");
            $stmt_resenha->execute([
                ':id_u' => $id_usuario,
                ':id_l' => $id_livro,
                ':nota' => $nota,
                ':msg' => $comentario_final
            ]);
            
            $_SESSION['sucesso_leitura'] = "Parabéns por terminar! Sua resenha já está no feed da Comunidade 🚀";
        } else {
            $_SESSION['sucesso_leitura'] = "Progresso atualizado com sucesso!";
        }

        $conn->commit();
        header("Location: leituras.php");
        exit;

    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['erro_leitura'] = "Erro ao atualizar leitura: " . $e->getMessage();
        header("Location: leituras.php");
        exit;
    }
}
?>
