<?php
session_start();
include __DIR__ . "/../includes/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST['acao'] ?? '';
    
    try {
        if ($acao == 'add') {
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $capa_nome = '';

            // Lidar com upload de arquivo
            if (isset($_FILES['capa_arquivo']) && $_FILES['capa_arquivo']['error'] == 0) {
                $extensao = strtolower(pathinfo($_FILES['capa_arquivo']['name'], PATHINFO_EXTENSION));
                $nome_novo = uniqid() . '.' . $extensao;
                $destino = __DIR__ . '/../capas/' . $nome_novo;
                
                if (move_uploaded_file($_FILES['capa_arquivo']['tmp_name'], $destino)) {
                    $capa_nome = $nome_novo; // Salva só o nome no banco
                }
            }

            $stmt = $conn->prepare("INSERT INTO recomendacao (titulo, descricao, capa) VALUES (:titulo, :descricao, :capa)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':capa' => $capa_nome
            ]);

            $_SESSION['msg_sucesso'] = "Recomendação adicionada com sucesso!";

        } elseif ($acao == 'edit') {
            $id_recomendacao = $_POST['id_recomendacao'];
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            // Verifica se tem arquivo novo
            if (isset($_FILES['capa_arquivo']) && $_FILES['capa_arquivo']['error'] == 0) {
                $extensao = strtolower(pathinfo($_FILES['capa_arquivo']['name'], PATHINFO_EXTENSION));
                $nome_novo = uniqid() . '.' . $extensao;
                $destino = __DIR__ . '/../capas/' . $nome_novo;
                
                if (move_uploaded_file($_FILES['capa_arquivo']['tmp_name'], $destino)) {
                    // Atualiza tudo, incluindo a capa
                    $stmt = $conn->prepare("UPDATE recomendacao SET titulo = :titulo, descricao = :descricao, capa = :capa WHERE id_recomendacao = :id");
                    $stmt->execute([
                        ':titulo' => $titulo,
                        ':descricao' => $descricao,
                        ':capa' => $nome_novo,
                        ':id' => $id_recomendacao
                    ]);
                }
            } else {
                // Atualiza apenas texto
                $stmt = $conn->prepare("UPDATE recomendacao SET titulo = :titulo, descricao = :descricao WHERE id_recomendacao = :id");
                $stmt->execute([
                    ':titulo' => $titulo,
                    ':descricao' => $descricao,
                    ':id' => $id_recomendacao
                ]);
            }

            $_SESSION['msg_sucesso'] = "Recomendação atualizada com sucesso!";

        } elseif ($acao == 'delete') {
            $id_recomendacao = $_POST['id_recomendacao'];

            // Tenta deletar
            try {
                $stmt = $conn->prepare("DELETE FROM recomendacao WHERE id_recomendacao = :id");
                $stmt->execute([':id' => $id_recomendacao]);
                $_SESSION['msg_sucesso'] = "Recomendação excluída com sucesso!";
            } catch (PDOException $e) {
                // Verifica se é erro de foreign key constraint (1451)
                if ($e->getCode() == '23000') {
                    $_SESSION['msg_erro'] = "Não é possível excluir este livro, pois há usuários com ele adicionado na lista de leituras ou resenhas vinculadas a ele.";
                } else {
                    $_SESSION['msg_erro'] = "Erro ao excluir: " . $e->getMessage();
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['msg_erro'] = "Ocorreu um erro na operação: " . $e->getMessage();
    }

    header("Location: ../gerenciar_recomendacoes.php");
    exit;
} else {
    header("Location: ../gerenciar_recomendacoes.php");
    exit;
}
