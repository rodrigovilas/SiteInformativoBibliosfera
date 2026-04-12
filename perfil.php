<?php
session_start();
include __DIR__ . "/database.php";

$nome_exibicao = "Usuário Bibliosfera";
$bio_exibicao = "Leitor apaixonado descobrindo novas histórias todos os dias nas páginas dos livros.";
$avatar_exibicao = "";
$usuario_logado = false;

if (isset($_SESSION['id_usuario'])) {
    $usuario_logado = true;
    $stmt = $conn->prepare("SELECT nome, usuario, bio, avatar FROM login WHERE id_usuario = :id");
    $stmt->bindParam(':id', $_SESSION['id_usuario']);
    $stmt->execute();
    $db_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($db_user) {
        $nome_exibicao = !empty($db_user['nome']) ? $db_user['nome'] : $db_user['usuario'];
        if (!empty($db_user['bio'])) $bio_exibicao = $db_user['bio'];
        if (!empty($db_user['avatar'])) $avatar_exibicao = $db_user['avatar'];
    }
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
				<li><a href="leituras.html">Leituras</a></li>
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
                    <p class="stat-number" id="stat-total">0</p>
                    <p class="stat-label">Adicionados</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="stat-reading">0</p>
                    <p class="stat-label">Lendo</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="stat-completed">0</p>
                    <p class="stat-label">Concluídos</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="stat-comments">0</p>
                    <p class="stat-label">Comentários</p>
                </div>
            </div>
		</section>

		
		<section class="profile-section">
			<h3>Meus Livros</h3>
			<div id="profile-books-container" class="cards">
				
			</div>
            <br>
            <div style="text-align: center; margin-top: 24px;">
                <a href="leituras.html" class="btn-secondary">Gerenciar Todas as Leituras</a>
            </div>
		</section>

		
		<section class="profile-section">
			<h3>Atividades Recentes</h3>
			<div id="profile-activities-container" class="activity-list">
				
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
            loadStatsAndBooks();
            loadActivities();
        });

        const profileModal = document.getElementById('profile-modal');

        function openProfileModal() {
            profileModal.style.display = 'flex';
        }

        function closeProfileModal() {
            profileModal.style.display = 'none';
        }

        function loadStatsAndBooks() {
            const books = JSON.parse(localStorage.getItem('readingBooks') || '[]');
            const container = document.getElementById('profile-books-container');

            let total = books.length;
            let reading = 0;
            let completed = 0;
            let totalComments = 0;

            container.innerHTML = '';

            if(total === 0) {
                container.innerHTML = '<div class="empty-state">Você ainda não listou nenhum livro. Vá até a aba Leituras e comece sua jornada!</div>';
            }

            books.forEach(book => {
                const perc = Math.min((book.currentPage / book.totalPages) * 100, 100);
                
                if(perc >= 100) completed++;
                else reading++;

                if(book.updates) {
                    book.updates.forEach(u => {
                        if(u.comment && u.comment.trim() !== '') totalComments++;
                    });
                }

                
                const card = document.createElement('article');
                card.className = 'progress-card';
                card.innerHTML = `
                    <div class="progress-info">
                        <h4 style="font-family: var(--fonte-titulo); color: #0f55b2; margin:0 0 8px 0; font-size: 20px;">📖 ${book.title}</h4>
                        <p style="margin:0; font-weight: 600; color: #666;">Por ${book.author}</p>
                        <p style="margin: 8px 0 0 0; font-family: var(--fonte-principal); font-size: 14px; font-weight: bold; color: #082f5b;">Progresso: ${Math.round(perc)}% (${book.currentPage} de ${book.totalPages} págs)</p>
                    </div>
                    <div class="progress-bar" style="margin-top:16px;">
                        <div class="progress-fill" style="width: ${perc}%"></div>
                    </div>
                `;
                container.appendChild(card);
            });

            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-reading').textContent = reading;
            document.getElementById('stat-completed').textContent = completed;
            document.getElementById('stat-comments').textContent = totalComments;
        }

        function loadActivities() {
            const updates = JSON.parse(localStorage.getItem('globalUpdates') || '[]');
            const container = document.getElementById('profile-activities-container');
            container.innerHTML = '';

            if(updates.length === 0) {
                container.innerHTML = '<div class="empty-state">Nenhuma atividade recente registrada em seus livros.</div>';
                return;
            }

            
            updates.slice(0, 10).forEach(u => {
                const date = new Date(u.date).toLocaleDateString('pt-BR', { day: '2-digit', month: 'short', year: 'numeric' });
                const item = document.createElement('div');
                item.className = 'activity-item';
                
                let commentHtml = u.comment && u.comment.trim() !== '' ? `<p class="activity-comment">"${u.comment}"</p>` : '';

                item.innerHTML = `
                    <div class="activity-header">
                        <span style="font-weight: 600; color: #1b76e3;">Página alcançada: ${u.pages}</span>
                        <span>🗓️ ${date}</span>
                    </div>
                    <h4 class="activity-title">📘 ${u.bookTitle} <small style="color:#666; font-size:15px; font-family: var(--fonte-corpo);">(${u.bookAuthor})</small></h4>
                    ${commentHtml}
                `;
                container.appendChild(item);
            });
        }
    </script>
</body>
</html>
