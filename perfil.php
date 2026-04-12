<?php
session_start();
include __DIR__ . "/database.php";

$nome_exibicao = "Usuário Bibliosfera";
$bio_exibicao = "Leitor apaixonado descobrindo novas histórias todos os dias nas páginas dos livros.";
$avatar_exibicao = "";
$usuario_logado = false;

if (isset($_SESSION['id_usuario'])) {
    $usuario_logado = true;
    $id_usuario = $_SESSION['id_usuario'];

    // 1. Dados do Usuário
    $stmt = $conn->prepare("SELECT nome, usuario, bio, avatar FROM login WHERE id_usuario = :id");
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();
    $db_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($db_user) {
        $nome_exibicao = !empty($db_user['nome']) ? $db_user['nome'] : $db_user['usuario'];
        if (!empty($db_user['bio'])) $bio_exibicao = $db_user['bio'];
        if (!empty($db_user['avatar'])) $avatar_exibicao = $db_user['avatar'];
    }

    // 2. Estatísticas do Banco de Dados
    // Total de livros na lista
    $stmt_total = $conn->prepare("SELECT COUNT(*) FROM listausuario WHERE id_usuario = :id");
    $stmt_total->execute(['id' => $id_usuario]);
    $total_livros = $stmt_total->fetchColumn();

    // Lendo
    $stmt_lendo = $conn->prepare("SELECT COUNT(*) FROM listausuario WHERE id_usuario = :id AND progresso = 'Lendo'");
    $stmt_lendo->execute(['id' => $id_usuario]);
    $lendo_livros = $stmt_lendo->fetchColumn();

    // Concluídos
    $stmt_fim = $conn->prepare("SELECT COUNT(*) FROM listausuario WHERE id_usuario = :id AND progresso = 'Terminado'");
    $stmt_fim->execute(['id' => $id_usuario]);
    $concluidos_livros = $stmt_fim->fetchColumn();

    // Comentários (Resenhas)
    $stmt_msg = $conn->prepare("SELECT COUNT(*) FROM resenha WHERE id_usuario = :id");
    $stmt_msg->execute(['id' => $id_usuario]);
    $total_comentarios = $stmt_msg->fetchColumn();

    // 3. Atividades (Últimas Resenhas do usuário)
    $stmt_atividades = $conn->prepare("
        SELECT r.resenha, r.data_resenha, l.titulo 
        FROM resenha r 
        JOIN livro l ON r.id_livro = l.id_livro 
        WHERE r.id_usuario = :id 
        ORDER BY r.data_resenha DESC LIMIT 5
    ");
    $stmt_atividades->execute(['id' => $id_usuario]);
    $atividades = $stmt_atividades->fetchAll(PDO::FETCH_ASSOC);

    // 4. Meus Livros (Tabela)
    $stmt_meus_livros = $conn->prepare("
        SELECT l.titulo, l.descricao, lu.progresso, lu.paginas_totais, lu.pagina_atual 
        FROM listausuario lu 
        JOIN livro l ON lu.id_livro = l.id_livro 
        WHERE lu.id_usuario = :id
    ");
    $stmt_meus_livros->execute(['id' => $id_usuario]);
    $meus_livros_db = $stmt_meus_livros->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Meu Perfil - Bibliosfera</title>

	<!-- Fontes (devem carregar antes do CSS) -->
	<link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Comic+Relief:wght@400;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

	<!-- CSS principal -->
	<link rel="stylesheet" href="home.css">
	<link rel="shortcut icon" href="img/logo.png">
	<script src="script.js"></script>

    <style>
        /* Perfil Específicos */
        .profile-main {
            display: flex;
            flex-direction: column;
            gap: 50px;
        }

        .profile-hero {
            display: flex;
            align-items: center;
            gap: 30px;
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15,85,178,0.08);
            border-left: 6px solid #ff9800;
        }

        .profile-avatar-container {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(15,85,178,0.15);
            flex-shrink: 0;
            background-color: #e0eaff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
        }

        .profile-avatar-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            flex-grow: 1;
        }

        .profile-info h2 {
            font-family: var(--fonte-titulo);
            color: #0f55b2;
            margin: 0 0 10px 0;
            font-size: 34px;
        }

        .profile-info p {
            font-family: var(--fonte-corpo);
            color: #666;
            font-size: 18px;
            margin: 0 0 20px 0;
            max-width: 600px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
        }

        .stat-card {
            background: #ffffff;
            padding: 30px 20px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(15,85,178,0.06);
            transition: transform 0.3s, box-shadow 0.3s;
            border-bottom: 4px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 28px rgba(15,85,178,0.12);
            border-bottom: 4px solid #ff9800;
        }

        .stat-number {
            font-family: var(--fonte-titulo);
            font-size: 42px;
            color: #0f55b2;
            margin: 0;
            line-height: 1;
        }

        .stat-label {
            font-family: var(--fonte-principal);
            font-size: 15px;
            font-weight: bold;
            color: #082f5b;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Atividades Recentes e Livros Grid */
        .profile-section h3 {
            font-family: var(--fonte-titulo);
            color: #0f55b2;
            font-size: 30px;
            margin-bottom: 24px;
            border-bottom: 4px solid #e0eaff;
            padding-bottom: 8px;
            display: inline-block;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .activity-item {
            background: #ffffff;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(15,85,178,0.05);
            border-left: 5px solid #0f55b2;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: transform 0.2s ease;
        }
        
        .activity-item:hover {
            transform: translateX(4px);
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: var(--fonte-principal);
            font-size: 14px;
            font-weight: 600;
            color: #666;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .activity-title {
            font-family: var(--fonte-titulo);
            color: #0f55b2;
            font-size: 20px;
            margin: 0;
        }
        
        .activity-comment {
            font-family: var(--fonte-corpo);
            font-size: 16px;
            color: #333;
            margin: 0;
        }

        .empty-state {
            text-align: center;
            color: #666;
            font-family: var(--fonte-corpo);
            font-size: 18px;
            padding: 30px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(15,85,178,0.05);
            grid-column: 1 / -1;
        }

        /* Responsive Settings */
        @media (max-width: 720px) {
            .profile-hero {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
            }
            .profile-avatar-container {
                width: 100px;
                height: 100px;
            }
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
					<a href="logout.php"
						class="btn-entrar"
						id="btnEntrar"
						type="button"
						title="Sair"
						aria-label="Sair">
						<img src="https://img.icons8.com/ios-filled/50/ff9800/login-rounded-right.png" alt="Sair">
						<span class="btn-text">Sair</span>
					</a>
				</li>
			</ul>
		</nav>
	</header>

	<main class="home profile-main">

		<section class="profile-hero">
            <div class="profile-avatar-container" id="profile-avatar-display">
              <?php if (!empty($avatar_exibicao)): ?>
                  <img src="<?= htmlspecialchars($avatar_exibicao) ?>" alt="Avatar">
              <?php else: ?>
                  📚
              <?php endif; ?>
            </div>
            <div class="profile-info">
                <h2 id="profile-name"><?= htmlspecialchars($nome_exibicao) ?></h2>
                <p id="profile-bio"><?= htmlspecialchars($bio_exibicao) ?></p>
                <div class="hero-actions">
                    <?php if ($usuario_logado): ?>
                        <button class="btn-primary" onclick="openProfileModal()">Editar Perfil</button>
                    <?php else: ?>
                        <a href="login.php" class="btn-primary" style="text-decoration:none;">Fazer Login para Editar</a>
                    <?php endif; ?>
                </div>
            </div>
		</section>

		<section class="profile-section">
			<h3>Minhas Estatísticas</h3>
			<div class="stats-grid">
                <div class="stat-card">
                    <p class="stat-number" id="stat-total"><?= $total_livros ?? 0 ?></p>
                    <p class="stat-label">Adicionados</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="stat-reading"><?= $lendo_livros ?? 0 ?></p>
                    <p class="stat-label">Lendo</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="stat-completed"><?= $concluidos_livros ?? 0 ?></p>
                    <p class="stat-label">Concluídos</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="stat-comments"><?= $total_comentarios ?? 0 ?></p>
                    <p class="stat-label">Comentários</p>
                </div>
            </div>
		</section>

		
		<section class="profile-section">
			<h3>Meus Livros</h3>
			<div id="profile-books-container" class="cards">
				<?php if (!empty($meus_livros_db)): ?>
                    <?php foreach ($meus_livros_db as $ml): ?>
                        <article class="progress-card">
                            <div class="progress-info">
                                <h4 style="font-family: var(--fonte-titulo); color: #0f55b2; margin:0 0 8px 0; font-size: 20px;">📖 <?= htmlspecialchars($ml['titulo']) ?></h4>
                                <p style="margin:0; font-weight: 600; color: #666; font-size: 14px;"><?= htmlspecialchars($ml['descricao']) ?></p>
                                <?php 
                                    $pct = $ml['paginas_totais'] > 0 ? min(round(($ml['pagina_atual'] / $ml['paginas_totais']) * 100), 100) : 0;
                                ?>
                                <p style="margin: 8px 0 0 0; font-family: var(--fonte-principal); font-size: 14px; font-weight: bold; color: #082f5b;">
                                    Progresso: <?= $pct ?>% (pág <?= $ml['pagina_atual'] ?>)
                                </p>
                                <div style="width: 100%; background: #eee; height: 8px; border-radius: 4px; margin-top: 5px; overflow: hidden;">
                                    <div style="width: <?= $pct ?>%; height: 100%; background: #ff9800;"></div>
                                </div>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #888;">Status: <?= htmlspecialchars($ml['progresso']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">Você ainda não tem livros no banco de dados. Vá até a aba Leituras!</div>
                <?php endif; ?>
			</div>
            <br>
            <div style="text-align: center; margin-top: 24px;">
                <a href="leituras.php" class="btn-secondary">Gerenciar Todas as Leituras</a>
            </div>
		</section>

		
		<section class="profile-section">
			<h3>Atividades Recentes</h3>
			<div id="profile-activities-container" class="activity-list">
				<?php if (!empty($atividades)): ?>
                    <?php foreach ($atividades as $at): ?>
                        <div class="activity-item">
                            <div class="activity-header">
                                <span style="font-weight: 600; color: #1b76e3;">Nova Resenha</span>
                                <span>🗓️ <?= date('d/m/Y', strtotime($at['data_resenha'])) ?></span>
                            </div>
                            <h4 class="activity-title">📘 <?= htmlspecialchars($at['titulo']) ?></h4>
                            <p class="activity-comment">"<?= htmlspecialchars($at['resenha']) ?>"</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">Nenhuma atividade recente registrada no banco de dados.</div>
                <?php endif; ?>
			</div>
		</section>

	</main>

    
	<div id="profile-modal" class="modal" style="display: none;">
		<div class="modal-content">
			<h3>Editar Perfil</h3>
			<form id="profile-form" action="update_perfil.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="input-name">Seu Nome:</label>
					<input type="text" name="nome" id="input-name" required value="<?= htmlspecialchars($nome_exibicao) ?>">
				</div>
                <div class="form-group">
					<label for="input-avatar">Nova Foto (opcional):</label>
					<input type="file" name="avatar" id="input-avatar" accept="image/jpeg, image/png, image/webp" style="font-family: var(--fonte-principal);">
				</div>
				<div class="form-group">
					<label for="input-bio">Sua Bio:</label>
					<textarea name="bio" id="input-bio" rows="3" placeholder="O que você gosta de ler?"><?= htmlspecialchars($bio_exibicao) ?></textarea>
				</div>
				<div class="modal-actions" style="display: flex; gap: 12px; margin-top: 24px;">
					<button type="button" class="btn-secondary" style="flex:1" onclick="closeProfileModal()">Cancelar</button>
					<button type="submit" class="btn-primary" style="flex:1; background: linear-gradient(135deg, #0f55b2, #1b76e3); color: white; border: none; border-radius: 999px; transition: 0.3s; box-shadow: 0 8px 18px rgba(15,85,178,0.25);">Salvar</button>
				</div>
			</form>
		</div>
	</div>

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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // As funções de carregamento do localStorage foram removidas pois agora usamos PHP + Banco de Dados
        });
    </script>
</body>
</html>
