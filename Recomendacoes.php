<?php
session_start();
include __DIR__ . "/includes/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

try {
    $stmt = $conn->query("SELECT * FROM recomendacao ORDER BY titulo ASC");
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
    <title>Recomendações - Bibliosfera</title>
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="shortcut icon" href="img/logo.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Relief:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        .livro-capa {
            width: 100%;
            max-width: 220px;
            aspect-ratio: 2 / 3;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            align-self: center;
        }
        .desc-livro {
            font-size: 0.9em;
            color: #555;
            margin-bottom: 5px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            text-align: justify;
            font-family: 'Sour Gummy', cursive;
        }
        .desc-livro.aberta {
            display: block;
            -webkit-line-clamp: unset;
        }
        .btn-expandir {
            background: none;
            border: none;
            color: #ff9800;
            cursor: pointer;
            font-family: 'Sour Gummy', cursive;
            font-weight: 600;
            padding: 0;
            margin-bottom: 15px;
            font-size: 0.85em;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-expandir:hover {
            text-decoration: underline;
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
                <li><a href="resenhas.html">Resenhas</a></li>
                <li><a href="videos.html">Vídeos</a></li>
                <li><a href="leituras.php">Leituras</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="comunidade.php">Comunidade</a></li>
                <li><a href="perfil.php">Meu Perfil</a></li>
                <li><a href="contato.php">Contato</a></li>
                <li class="nav-login">
                    <a href="actions/logout.php"
                        class="btn-entrar"
                        id="btnEntrar"
                        type="button"
                        title="Sair"
                        aria-haspopup="dialog"
                        aria-controls="loginModal"
                        aria-label="Sair">
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
                <h2>Recomendações de Leitura</h2>
                <p>
                    Explore as nossas recomendações e descubra a sua próxima grande leitura. 
                    Encontre diversos gêneros e adicione os livros à sua lista!
                </p>

                <div class="hero-actions">
                    <a href="#catalogo-grid" class="btn-primary" role="button" aria-label="Ver Recomendações" data-scroll>Ver Recomendações</a>
                    <a href="leituras.php" class="btn-secondary" role="button" aria-label="Ir para Leituras">Minhas Leituras</a>
                </div>
            </div>

            <div class="hero-image">
                <img src="img/resenha.png" alt="Ilustração de livros">
            </div>
        </section>

        <section class="section" id="catalogo-grid">
            <h3>Obras Disponíveis (<?= count($livros) ?>)</h3>

            <div class="cards">
                <?php foreach ($livros as $livro): ?>
                    <?php
                    // Corrige o caminho da capa
                    $nome_arquivo = basename($livro['capa']);
                    
                    // Tratamento de erros de digitação no BD (.9jpg -> 9.jpg)
                    if (preg_match('/^\.([0-9]+)jpg$/', $nome_arquivo, $matches)) {
                        $nome_arquivo = $matches[1] . '.jpg';
                    }
                    
                    $caminho_capa = "capas/" . $nome_arquivo;
                    
                    // Tratamento especial se o nome for 58.jpg mas o arquivo for 58.jpeg
                    if ($nome_arquivo == '58.jpg' && !file_exists(__DIR__ . '/' . $caminho_capa)) {
                        if (file_exists(__DIR__ . "/capas/58.jpeg")) {
                            $caminho_capa = "capas/58.jpeg";
                        }
                    }

                    // Se a capa ainda não existir, exibe uma imagem padrão
                    if (!file_exists(__DIR__ . "/" . $caminho_capa)) {
                        $caminho_capa = "img/resenha.png"; 
                    }

                    // Trata a descrição "TBA"
                    $descricao = trim($livro['descricao']);
                    if (empty($descricao) || strpos($descricao, 'TBA') === 0) {
                        $descricao = "Uma excelente recomendação de leitura para você conhecer. Explore novos mundos com esta obra incrível.";
                    }
                    ?>
                    <article class="card">
                        <img src="<?= htmlspecialchars($caminho_capa) ?>" alt="Capa do livro <?= htmlspecialchars($livro['titulo']) ?>" class="livro-capa">
                        <span class="tag">Recomendação</span>
                        <h4 style="margin-top: 10px;"><?= htmlspecialchars($livro['titulo']) ?></h4>
                        <div id="desc-<?= $livro['id_recomendacao'] ?>" class="desc-livro">
                            <?= nl2br(htmlspecialchars($descricao)) ?>
                        </div>
                        <button class="btn-expandir" id="btn-exp-<?= $livro['id_recomendacao'] ?>" onclick="toggleDescricao(<?= $livro['id_recomendacao'] ?>)">
                            Ler mais <span>▾</span>
                        </button>
                    </article>
                <?php endforeach; ?>
            </div>
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

    <script>
        function toggleDescricao(id) {
            const desc = document.getElementById('desc-' + id);
            const btn = document.getElementById('btn-exp-' + id);
            
            if (desc.classList.contains('aberta')) {
                desc.classList.remove('aberta');
                btn.innerHTML = 'Ler mais <span>▾</span>';
            } else {
                desc.classList.add('aberta');
                btn.innerHTML = 'Mostrar menos <span>▴</span>';
            }
        }

        // Funcionalidade de smooth scroll (opcional se não estiver no script.js)
        document.querySelectorAll('a[data-scroll]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
