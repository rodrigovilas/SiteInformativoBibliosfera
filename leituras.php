<?php
session_start();
include __DIR__ . "/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$meus_livros = [];

try {
    // Busca os livros que o usuário está lendo, incluindo as páginas
    $stmt = $conn->prepare("SELECT l.id_livro, l.titulo, l.descricao, lu.paginas_totais, lu.pagina_atual, lu.progresso 
                            FROM listausuario lu 
                            JOIN livro l ON lu.id_livro = l.id_livro 
                            WHERE lu.id_usuario = :id");
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();
    $meus_livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Busca o histórico de progresso para cada livro
    foreach ($meus_livros as &$livro) {
        $stmt_h = $conn->prepare("SELECT pagina, comentario, data_registro FROM historico_leitura 
                                  WHERE id_usuario = :id_u AND id_livro = :id_l 
                                  ORDER BY data_registro DESC");
        $stmt_h->execute(['id_u' => $id_usuario, 'id_l' => $livro['id_livro']]);
        $livro['historico'] = $stmt_h->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($livro); // Limpa a referência para evitar duplicação no próximo loop
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Leituras - Bibliosfera</title>
	<link rel="stylesheet" href="home.css">
	<link rel="shortcut icon" href="img/logo.png">
	<script src="script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Relief:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .progress-container {
            width: 100%;
            background-color: #eee;
            border-radius: 10px;
            margin: 10px 0;
            height: 12px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff9800, #f57c00);
            transition: width 0.5s ease-in-out;
        }
        .history-list {
            margin-top: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 10px;
            font-size: 0.9em;
            display: none;
        }
        .history-item {
            border-bottom: 1px solid #ddd;
            padding: 5px 0;
        }
        .history-item:last-child { border-bottom: none; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body id="topo" style="font-family: 'Sour Gummy', cursive;">

	<header>
		<div class="dvh">
			<a href="home.html" class="topo" title="Ir para o início" aria-label="Ir para o início da página">
				<img src="img/logo.png" alt="Logo da Bibliosfera" class="logo" />
				<h1 class="tituloh">Bibliosfera</h1>
			</a>
		</div>

		<nav class="nav">
			<ul class="navlinks">
				<li><a href="resenhas.html">Resenhas</a></li>
				<li><a href="videos.html">Vídeos</a></li>
				<li><a href="leituras.php">Leituras</a></li>
				<li><a href="blog.html">Blog</a></li>
				<li><a href="comunidade.php">Comunidade</a></li>
				<li><a href="perfil.php">Meu Perfil</a></li>
				<li><a href="contato.php">Contato</a></li>
				<li class="nav-login">
					<a href="logout.php" class="btn-entrar" id="btnEntrar" title="Sair">
						<img src="https://img.icons8.com/ios-filled/50/ff9800/login-rounded-right.png" alt="Sair">
						<span class="btn-text">Sair</span>
					</a>
				</li>
			</ul>
		</nav>
	</header>

	<main class="home">

		<section class="hero">
			<div class="hero-text">
				<h2>Meu Progresso de Leitura</h2>
				<p>Acompanhe suas leituras, registre notas privadas e compartilhe a resenha final quando terminar!</p>
				<div class="hero-actions">
					<button onclick="document.getElementById('add-book-form').style.display='flex'" class="btn-primary">Adicionar Livro</button>
					<a href="comunidade.php" class="btn-secondary">Ver Comunidade</a>
				</div>
			</div>
			<div class="hero-image">
				<img src="img/ler.webp" alt="Ilustração de leitura">
			</div>
		</section>

        <!-- Mensagens de Feedback -->
        <?php if(isset($_SESSION['sucesso_leitura'])): ?>
            <p style="color: green; text-align: center; font-weight: bold;"><?= $_SESSION['sucesso_leitura']; unset($_SESSION['sucesso_leitura']); ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION['erro_leitura'])): ?>
            <p style="color: red; text-align: center; font-weight: bold;"><?= $_SESSION['erro_leitura']; unset($_SESSION['erro_leitura']); ?></p>
        <?php endif; ?>

		<section id="add-book-form" class="section add-book-container" style="display: none;">
			<div class="card form-card">
				<h3>Adicionar Novo Livro</h3>
				<form action="processar_livro.php" method="POST">
					<div class="form-group">
						<label>Título do Livro:</label>
						<input type="text" name="titulo" placeholder="Ex: O Pequeno Príncipe" required>
					</div>
					<div class="form-group">
						<label>Autor:</label>
						<input type="text" name="autor" placeholder="Ex: Antoine de Saint-Exupéry" required>
					</div>
                    <div class="form-group">
						<label>Total de Páginas:</label>
						<input type="number" name="paginas_totais" placeholder="Ex: 100" min="1" required>
					</div>
					<div class="form-actions">
						<button type="button" onclick="document.getElementById('add-book-form').style.display='none'" class="btn-secondary">Cancelar</button>
						<button type="submit" class="btn-primary">Começar Leitura</button>
					</div>
				</form>
			</div>
		</section>

		<section class="section">
			<h3>Minhas Leituras</h3>
			<div class="cards">
				<?php if (count($meus_livros) > 0): ?>
					<?php foreach ($meus_livros as $livro): 
                        $pct = $livro['paginas_totais'] > 0 ? min(round(($livro['pagina_atual'] / $livro['paginas_totais']) * 100), 100) : 0;
                    ?>
						<article class="card">
							<div class="progress-info">
								<h4>📖 <?= htmlspecialchars($livro['titulo']) ?></h4>
								<p class="author-text"><?= htmlspecialchars($livro['descricao']) ?></p>
                                <p style="font-size: 0.9em; color: #666; margin-top: 5px;">
                                    Progresso: <strong><?= $livro['pagina_atual'] ?></strong> de <strong><?= $livro['paginas_totais'] ?></strong> págs (<?= $pct ?>%)
                                </p>
                                <div class="progress-container">
                                    <div class="progress-bar-fill" style="width: <?= $pct ?>%;"></div>
                                </div>
                                <p style="font-size: 0.8em; font-weight: bold; color: #0f55b2;">Status: <?= $livro['progresso'] ?></p>
							</div>
							<div class="progress-actions" style="margin-top: 15px; display: flex; flex-direction: column; gap: 8px;">
								<button onclick="openUpdateModal(<?= $livro['id_livro'] ?>, '<?= addslashes($livro['titulo']) ?>', <?= $livro['pagina_atual'] ?>, <?= $livro['paginas_totais'] ?>, '<?= $livro['progresso'] ?>')" class="btn-primary" style="width: 100%;">Atualizar Progresso</button>
                                
                                <a href="excluir_livro.php?id=<?= $livro['id_livro'] ?>" 
                                   onclick="return confirm('Tem certeza que deseja remover este livro da sua lista? Isso também apagará seu histórico privado de notas.')" 
                                   style="text-align: center; color: #e53935; text-decoration: none; font-size: 14px; font-weight: bold; border: 2px solid #e53935; padding: 8px; border-radius: 99px; transition: 0.3s;"
                                   onmouseover="this.style.background='#e53935'; this.style.color='white';"
                                   onmouseout="this.style.background='transparent'; this.style.color='#e53935';">
                                   🗑️ Excluir Livro
                                </a>

                                <?php if (!empty($livro['historico'])): ?>
                                    <button onclick="toggleHistory(<?= $livro['id_livro'] ?>)" class="btn-secondary" style="width: 100%; border-radius: 99px; font-size: 14px;">📜 Ver Histórico Privado</button>
                                    <div id="hist-<?= $livro['id_livro'] ?>" class="history-list">
                                        <strong>Notas anteriores:</strong>
                                        <?php foreach ($livro['historico'] as $h): ?>
                                            <div class="history-item">
                                                <small><?= date('d/m/y', strtotime($h['data_registro'])) ?> - Pág <?= $h['pagina'] ?>:</small><br>
                                                "<?= htmlspecialchars($h['comentario']) ?>"
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				<?php else: ?>
					<p style="text-align: center; width: 100%;">Você ainda não adicionou livros. Comece agora!</p>
				<?php endif; ?>
			</div>
		</section>

	</main>

	<!-- Modal de Atualização -->
	<div id="update-modal" class="modal" style="display: none; align-items: center; justify-content: center; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000;">
		<div class="modal-content" style="background: white; padding: 25px; border-radius: 20px; max-width: 450px; width: 90%;">
			<h3 id="modal-title">Atualizar Leitura</h3>
			<form action="processar_atualizacao.php" method="POST">
				<input type="hidden" name="id_livro" id="modal-id-livro">
				
                <div class="form-group" style="margin-bottom: 15px;">
					<label>Status:</label>
					<select name="status" id="status-select" onchange="toggleReviewFields()" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                        <option value="Lendo">Lendo</option>
                        <option value="Pausado">Pausado</option>
                        <option value="Terminado">Terminado (Postar Resenha 🔥)</option>
                        <option value="Largado">Largado</option>
                    </select>
				</div>

                <div class="form-group" style="margin-bottom: 15px;">
					<label>Página onde parou:</label>
					<input type="number" name="pagina_atual" id="modal-pagina-atual" min="0" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
				</div>

                <div class="form-group" style="margin-bottom: 15px;">
					<label>Nota/Comentário Privado (Opcional):</label>
					<textarea name="comentario_progresso" rows="2" placeholder="O que achou deste trecho? (Fica só para você)" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;"></textarea>
				</div>

                <!-- Seção Publica da Comunidade -->
                <div id="review-fields" style="display: none; border-top: 2px dashed #ff9800; padding-top: 15px; margin-top: 15px; animation: fadeIn 0.3s;">
                    <p style="color: #ff9800; font-weight: bold; font-size: 0.9em; margin-bottom: 10px;">🌟 Publicar na Comunidade:</p>
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label>Nota Final (0 a 10):</label>
                        <input type="number" name="nota" min="0" max="10" step="0.5" value="10" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div class="form-group">
                        <label>Resenha Final:</label>
                        <textarea name="comentario" rows="3" placeholder="Sua opinião final para todos verem..." style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;"></textarea>
                    </div>
                </div>

				<div class="modal-actions" style="display: flex; gap: 10px; margin-top: 20px;">
					<button type="button" onclick="document.getElementById('update-modal').style.display='none'" class="btn-secondary" style="flex: 1;">Voltar</button>
					<button type="submit" class="btn-primary" style="flex: 1;">Salvar</button>
				</div>
			</form>
		</div>
	</div>

	<script>
		function openUpdateModal(id, titulo, pgAt, pgTot, status) {
			document.getElementById('modal-id-livro').value = id;
			document.getElementById('modal-title').innerText = "Atualizar: " + titulo;
			document.getElementById('status-select').value = status;
            document.getElementById('modal-pagina-atual').value = pgAt;
            document.getElementById('modal-pagina-atual').max = pgTot;
            toggleReviewFields();
			document.getElementById('update-modal').style.display = 'flex';
		}

        function toggleReviewFields() {
            const status = document.getElementById('status-select').value;
            const fields = document.getElementById('review-fields');
            fields.style.display = (status === 'Terminado') ? 'block' : 'none';
        }

        function toggleHistory(id) {
            const hist = document.getElementById('hist-' + id);
            hist.style.display = (hist.style.display === 'block') ? 'none' : 'block';
        }
	</script>

<footer style="width: 100%; background-color: #0f55b2; padding: 25px 0; margin-top: auto; display: block; box-shadow: 0 -2px 10px #0f55b2;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 50px; display: flex; justify-content: center; align-items: center; position: relative; box-sizing: border-box;">
        
        <span style="color: #ffffff; font-family: 'Sour Gummy', cursive; font-size: 18px; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            © 2026 - Todos os direitos reservados
        </span>

        <a href="#topo" style="position: absolute; right: 50px; border: 2px solid #ffffff; color: #ffffff; text-decoration: none; padding: 8px 18px; border-radius: 12px; font-family: 'Sour Gummy', cursive; font-weight: 600; font-size: 16px; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            Voltar ao topo
        </a>
        
    </div>
</footer>

</body>
</html>
