<?php
session_start();
include __DIR__ . "/database.php";

$resenhas = [];

try {
    // Busca todas as resenhas juntando com os dados do livro e do usuário
    $sql = "SELECT 
                r.resenha, 
                r.nota, 
                r.data_resenha, 
                l.titulo AS livro_titulo, 
                l.capa AS livro_capa, 
                u.nome AS usuario_nome, 
                u.usuario AS usuario_arroba, 
                u.avatar AS usuario_avatar
            FROM resenha r
            JOIN livro l ON r.id_livro = l.id_livro
            JOIN login u ON r.id_usuario = u.id_usuario
            ORDER BY r.data_resenha DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resenhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao carregar a comunidade: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resenhas - Bibliosfera</title>
    <link rel="stylesheet" href="home.css">
    <link rel="shortcut icon" href="img/logo.png">
    <script src="script.js"></script>
    <!-- Fontes -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Relief:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
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
                        title="Entrar"
                        aria-haspopup="dialog"
                        aria-controls="loginModal"
                        aria-label="Entrar">
                        <img src="https://img.icons8.com/ios-filled/50/ff9800/login-rounded-right.png" alt="Entrar">
                        <span class="btn-text">Sair</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="home">

        <!-- HERO RESENHAS -->
        <section class="hero">
            <div class="hero-text">
                <h2>Comentários da Comunidade</h2>
                <p>
                    Análises, críticas e comentários reais feitos por nossos leitores.
                </p>

                <div class="hero-actions">
                    <!-- Opcional futuramente: linkar para adicionar comentário -->
                    <a href="#resenhas-grid" class="btn-primary" role="button" aria-label="Ver Resenhas" data-scroll>Ler Comentários</a>
                    <a href="home.html" class="btn-secondary" role="button" aria-label="Voltar ao Início" data-scroll>Voltar ao Início</a>
                </div>
            </div>

            <div class="hero-image">
                <img src="img/pngtree-3d-comment-icon-design-vector-png-image_6130577.png" alt="Ilustração de comentários">
            </div>
        </section>

        <!-- FEED DE RESENHAS DINÂMICO -->
        <section class="section" id="resenhas-grid">
            <h3>Feed da Comunidade</h3>

            <div class="cards">
                <?php if (count($resenhas) > 0): ?>
                    <?php foreach ($resenhas as $r): ?>
                        <article class="card">
                            
                            <!-- Cabeçalho do Card: Identificação do Usuário -->
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                                <?php if (!empty($r['usuario_avatar'])): ?>
                                    <img src="<?php echo htmlspecialchars($r['usuario_avatar']); ?>" alt="Avatar" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #0f55b2;">
                                <?php else: ?>
                                    <!-- Fallback caso não tenha avatar -->
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background-color: #eee; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #0f55b2; border: 2px solid #0f55b2;">
                                        <?php echo strtoupper(substr($r['usuario_nome'] ?? $r['usuario_arroba'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div style="text-align: left; line-height: 1.2;">
                                    <strong style="color: #0f55b2; font-family: 'Sour Gummy', cursive; font-size: 1.1rem;">
                                        <?php echo htmlspecialchars($r['usuario_nome'] ?? $r['usuario_arroba']); ?>
                                    </strong>
                                    <br>
                                    <span style="font-size: 0.85rem; color: #777;">
                                        <?php echo date('d/m/Y \à\s H:i', strtotime($r['data_resenha'])); ?>
                                    </span>
                                </div>
                            </div>

                            <span class="tag">Nota: <?php echo htmlspecialchars(number_format($r['nota'], 1, ',', '')); ?> / 10</span>
                            <h4 style="margin-top: 10px;">📚 <?php echo htmlspecialchars($r['livro_titulo']); ?></h4>
                            
                            <p class="resenhatxt" style="font-style: italic; color: #333; margin-top: 15px; margin-bottom: 15px;">
                                "<?php echo nl2br(htmlspecialchars($r['resenha'])); ?>"
                            </p>
                            
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Mensagem para quando o banco não tem resenhas ainda -->
                    <div style="text-align: center; width: 100%; padding: 40px; background-color: #f9f9f9; border-radius: 12px;">
                        <img src="img/livrohome.png" alt="Livro vazio" style="width: 100px; margin-bottom: 20px; opacity: 0.5;">
                        <h4 style="color: #666; font-family: 'Inter', sans-serif;">Nenhum comentário por aqui ainda...</h4>
                        <p style="color: #999;">O feed está vazio. Seja o primeiro a avaliar um livro e compartilhar seus pensamentos com a comunidade!</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- CTA FINAL -->
        <section class="cta">
            <h3>Explore mais livros</h3>
            <p>
                Descubra análises, vídeos e reflexões que expandem sua forma de enxergar a leitura.
            </p>
            <a href="leituras.html" class="btn-primary">Começar agora</a>
        </section>

    </main>

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
