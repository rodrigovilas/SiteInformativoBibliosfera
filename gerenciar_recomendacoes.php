<?php
session_start();
include __DIR__ . "/includes/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

try {
    $stmt = $conn->query("SELECT * FROM recomendacao ORDER BY id_recomendacao DESC");
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $livros = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Recomendações - Bibliosfera</title>
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="shortcut icon" href="img/logo.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .admin-table-wrapper {
            background: white;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .admin-table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
        }
        .admin-table tr:hover {
            background-color: #fdfdfd;
        }
        .capa-preview {
            width: 50px;
            height: 75px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .action-btns {
            display: flex;
            gap: 10px;
        }
        .btn-edit {
            background-color: #0f55b2;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85em;
            text-decoration: none;
        }
        .btn-delete {
            background-color: #e53935;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85em;
        }
        
        /* Modal Estilos */
        .modal {
            display: none; 
            position: fixed; 
            top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.5); 
            z-index: 1000;
            align-items: center; 
            justify-content: center;
        }
        .modal-content {
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            width: 90%; 
            max-width: 500px;
            position: relative;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        .form-group input[type="text"], 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: inherit;
        }
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            background: #f8f9fa;
            border: 1px dashed #ccc;
            border-radius: 6px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
        .alert-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .alert-error { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .desc-text {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body id="topo">

    <header>
        <div class="dvh">
            <a href="home.html" class="topo" title="Ir para o início" aria-label="Ir para o início da página">
                <img src="img/logo.png" alt="Logo da Bibliosfera" class="logo" />
                <h1 class="tituloh">Bibliosfera</h1>
            </a>
        </div>
        <nav class="nav">
            <ul class="navlinks">
                <li><a href="Recomendacoes.php">← Voltar às Recomendações</a></li>
                <li class="nav-login">
                    <a href="actions/logout.php" class="btn-entrar" id="btnEntrar" title="Sair">
                        <img src="https://img.icons8.com/ios-filled/50/ff9800/login-rounded-right.png" alt="Sair">
                        <span class="btn-text">Sair</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="home" style="min-height: 80vh; background-color: #f4f6fb;">
        <div class="admin-container">
            
            <?php if(isset($_SESSION['msg_sucesso'])): ?>
                <div class="alert alert-success"><?= $_SESSION['msg_sucesso']; unset($_SESSION['msg_sucesso']); ?></div>
            <?php endif; ?>
            <?php if(isset($_SESSION['msg_erro'])): ?>
                <div class="alert alert-error"><?= $_SESSION['msg_erro']; unset($_SESSION['msg_erro']); ?></div>
            <?php endif; ?>

            <div class="admin-header">
                <h2>Gerenciar Recomendações de Leitura</h2>
                <button class="btn-primary" onclick="abrirModal('add')">+ Adicionar Recomendação</button>
            </div>

            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Capa</th>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livros as $livro): ?>
                            <?php
                                $nome_arquivo = basename($livro['capa']);
                                if (preg_match('/^\.([0-9]+)jpg$/', $nome_arquivo, $matches)) {
                                    $nome_arquivo = $matches[1] . '.jpg';
                                }
                                $caminho_capa = "capas/" . $nome_arquivo;
                                if ($nome_arquivo == '58.jpg' && !file_exists(__DIR__ . '/' . $caminho_capa)) {
                                    if (file_exists(__DIR__ . "/capas/58.jpeg")) $caminho_capa = "capas/58.jpeg";
                                }
                                if (!file_exists(__DIR__ . "/" . $caminho_capa)) {
                                    $caminho_capa = "img/resenha.png"; 
                                }
                            ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($caminho_capa) ?>" alt="Capa" class="capa-preview"></td>
                                <td>#<?= $livro['id_recomendacao'] ?></td>
                                <td><strong><?= htmlspecialchars($livro['titulo']) ?></strong></td>
                                <td class="desc-text"><?= htmlspecialchars($livro['descricao']) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="btn-edit" onclick="abrirModal('edit', <?= $livro['id_recomendacao'] ?>, '<?= addslashes(htmlspecialchars($livro['titulo'])) ?>', '<?= addslashes(htmlspecialchars($livro['descricao'])) ?>')">Editar</button>
                                        <form action="actions/recomendacoes_action.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta recomendação?');" style="display:inline;">
                                            <input type="hidden" name="acao" value="delete">
                                            <input type="hidden" name="id_recomendacao" value="<?= $livro['id_recomendacao'] ?>">
                                            <button type="submit" class="btn-delete">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Adicionar/Editar Livro -->
    <div id="livro-modal" class="modal">
        <div class="modal-content">
            <h3 id="modal-titulo-texto" style="margin-top: 0; color: #0f55b2; margin-bottom: 20px;">Adicionar Recomendação</h3>
            <form action="actions/recomendacoes_action.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="acao" id="modal-acao" value="add">
                <input type="hidden" name="id_recomendacao" id="modal-id" value="">
                
                <div class="form-group">
                    <label>Título do Livro</label>
                    <input type="text" name="titulo" id="modal-titulo-input" required placeholder="Digite o título do livro">
                </div>
                
                <div class="form-group">
                    <label>Descrição / Sinopse</label>
                    <textarea name="descricao" id="modal-descricao-input" rows="4" placeholder="Escreva uma breve sinopse sobre a obra..."></textarea>
                </div>
                
                <div class="form-group">
                    <label>Capa do Livro (Imagem)</label>
                    <input type="file" name="capa_arquivo" accept="image/*">
                    <small style="color: #666; display: block; margin-top: 5px;" id="modal-capa-dica">
                        Se não selecionar uma imagem nova, a atual será mantida.
                    </small>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="button" class="btn-secondary" style="flex: 1;" onclick="fecharModal()">Cancelar</button>
                    <button type="submit" class="btn-primary" style="flex: 1;">Salvar Livro</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(acao, id = null, titulo = '', descricao = '') {
            document.getElementById('livro-modal').style.display = 'flex';
            document.getElementById('modal-acao').value = acao;
            
            if (acao === 'edit') {
                document.getElementById('modal-titulo-texto').innerText = 'Editar Recomendação';
                document.getElementById('modal-id').value = id;
                document.getElementById('modal-titulo-input').value = titulo;
                document.getElementById('modal-descricao-input').value = descricao;
                document.getElementById('modal-capa-dica').style.display = 'block';
            } else {
                document.getElementById('modal-titulo-texto').innerText = 'Adicionar Nova Recomendação';
                document.getElementById('modal-id').value = '';
                document.getElementById('modal-titulo-input').value = '';
                document.getElementById('modal-descricao-input').value = '';
                document.getElementById('modal-capa-dica').style.display = 'none';
            }
        }

        function fecharModal() {
            document.getElementById('livro-modal').style.display = 'none';
        }

        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            let modal = document.getElementById('livro-modal');
            if (event.target == modal) {
                fecharModal();
            }
        }
    </script>
</body>
</html>
