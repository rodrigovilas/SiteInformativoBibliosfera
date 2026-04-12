<?php
session_start();
include __DIR__ . "/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES, 'UTF-8');
    $autor = htmlspecialchars($_POST['autor'], ENT_QUOTES, 'UTF-8');
    $paginas_totais = intval($_POST['paginas_totais']);
    $id_usuario = $_SESSION['id_usuario'];

    try {
        // 1. Insere o livro na tabela 'livro'
        $stmt = $conn->prepare("INSERT INTO livro (titulo, descricao) VALUES (:titulo, :descricao)");
        $descricao = "Autor: " . $autor;
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->execute();
        
        $id_livro = $conn->lastInsertId();

        // 2. Associa o livro ao usuário na tabela 'listausuario' com as páginas totais
        $stmt2 = $conn->prepare("INSERT INTO listausuario (id_usuario, id_livro, progresso, paginas_totais, pagina_atual) VALUES (:id_u, :id_l, 'Lendo', :p_tot, 0)");
        $stmt2->bindParam(':id_u', $id_usuario);
        $stmt2->bindParam(':id_l', $id_livro);
        $stmt2->bindParam(':p_tot', $paginas_totais);
        $stmt2->execute();

        $_SESSION['sucesso_leitura'] = "Livro adicionado com sucesso!";
        header("Location: leituras.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro_leitura'] = "Erro ao adicionar livro: " . $e->getMessage();
        header("Location: leituras.php");
        exit;
    }
}
?>
