<?php
session_start();
include __DIR__ . "/includes/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

/*
 * CATÁLOGO DE LIVROS
 * ──────────────────
 * Os livros são definidos neste array. Cada entrada precisa de:
 *   - capa    : nome do arquivo dentro da pasta capas/ (ex: "dom-casmurro.jpg")
 *   - titulo  : título do livro
 *   - autor   : nome do autor
 *   - ano     : ano de publicação
 *   - sinopse : texto curto que aparece ao clicar em "Ler mais"
 *
 * Para adicionar um novo livro basta incluir um novo item no array abaixo
 * e colocar a imagem correspondente na pasta capas/.
 */
$livros = [
    [
        "capa"    => "dom-casmurro.jpg",
        "titulo"  => "Dom Casmurro",
        "autor"   => "Machado de Assis",
        "ano"     => 1899,
        "sinopse" => "Narra a história de Bentinho, que mais tarde ficará conhecido como Dom Casmurro, e seu amor pela vizinha Capitu. A obra levanta questões sobre ciúme, traição e memória, sendo um dos maiores clássicos do Realismo brasileiro."
    ],
    [
        "capa"    => "memorias-postumas.jpg",
        "titulo"  => "Memórias Póstumas de Brás Cubas",
        "autor"   => "Machado de Assis",
        "ano"     => 1881,
        "sinopse" => "Narrado por um defunto-autor, o romance é uma crítica irônica à sociedade burguesa brasileira do século XIX. Considerado o marco inicial do Realismo no Brasil, é leitura obrigatória em praticamente todos os vestibulares."
    ],
    [
        "capa"    => "o-cortico.jpg",
        "titulo"  => "O Cortiço",
        "autor"   => "Aluísio Azevedo",
        "ano"     => 1890,
        "sinopse" => "Retrata a vida coletiva em um cortiço no Rio de Janeiro do século XIX, explorando temas como exploração, ambição e determinismo social. Obra central do Naturalismo brasileiro e presença constante nos vestibulares."
    ],
    [
        "capa"    => "iracema.jpg",
        "titulo"  => "Iracema",
        "autor"   => "José de Alencar",
        "ano"     => 1865,
        "sinopse" => "Lenda poética que narra o amor entre a índia Iracema e o guerreiro português Martim. Uma das obras fundadoras do Romantismo indianista brasileiro, rica em simbolismo e linguagem poética."
    ],
    [
        "capa"    => "vidas-secas.jpg",
        "titulo"  => "Vidas Secas",
        "autor"   => "Graciliano Ramos",
        "ano"     => 1938,
        "sinopse" => "Acompanha a família de Fabiano, retirante nordestino que foge da seca com a mulher, dois filhos e a cachorra Baleia. Uma das obras mais importantes do Modernismo e do Regionalismo brasileiro, cobrada com frequência no ENEM."
    ],
    [
        "capa"    => "grande-sertao.jpg",
        "titulo"  => "Grande Sertão: Veredas",
        "autor"   => "João Guimarães Rosa",
        "ano"     => 1956,
        "sinopse" => "Monólogo do ex-jagunço Riobaldo, que rememora sua vida no sertão mineiro e um possível pacto com o diabo. Considerado o maior romance da literatura brasileira, é referência obrigatória para vestibulares de alta concorrência."
    ],
    [
        "capa"    => "auto-da-compadecida.jpg",
        "titulo"  => "Auto da Compadecida",
        "autor"   => "Ariano Suassuna",
        "ano"     => 1955,
        "sinopse" => "Peça teatral que mescla o folclore nordestino com o Auto de Gil Vicente. Narra as aventuras dos pícaros João Grilo e Chicó. Uma das obras mais cobradas em vestibulares por sua riqueza cultural e linguística."
    ],
    [
        "capa"    => "o-guarani.jpg",
        "titulo"  => "O Guarani",
        "autor"   => "José de Alencar",
        "ano"     => 1857,
        "sinopse" => "Romance histórico que narra o amor entre o índio Peri e a jovem branca Ceci no Brasil colonial. Marco do Romantismo indianista, explora temas de identidade nacional, natureza e heroísmo."
    ],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Livros - Bibliosfera</title>
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="shortcut icon" href="img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        /* ── Variáveis ─────────────────────────────────────── */
        :root {
            --azul:       #0f55b2;
            --laranja:    #ff9800;
            --laranja-dk: #f57c00;
            --cinza-cl:   #f4f6fb;
            --cinza-md:   #e0e6f0;
            --texto:      #1a1a2e;
            --texto-suave:#555;
            --branco:     #ffffff;
            --radius:     16px;
            --sombra:     0 8px 32px rgba(15,85,178,0.10);
            --sombra-hov: 0 16px 48px rgba(15,85,178,0.18);
        }

        /* ── Reset pontual ──────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Sour Gummy', cursive;
            background: var(--cinza-cl);
            color: var(--texto);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Hero do catálogo ───────────────────────────────── */
        .catalogo-hero {
            background: linear-gradient(135deg, var(--azul) 0%, #1a3a6e 100%);
            color: var(--branco);
            padding: 60px 40px 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .catalogo-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .catalogo-hero h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 700;
            margin: 0 0 12px;
            position: relative;
        }
        .catalogo-hero p {
            font-size: 1.05rem;
            opacity: 0.88;
            max-width: 560px;
            margin: 0 auto 28px;
            line-height: 1.6;
            position: relative;
        }
        .btn-voltar {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            color: var(--branco);
            border: 2px solid rgba(255,255,255,0.4);
            padding: 10px 24px;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.25s, border-color 0.25s;
            position: relative;
        }
        .btn-voltar:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.7);
        }

        /* ── Contagem de resultados ─────────────────────────── */
        .catalogo-info {
            max-width: 1200px;
            margin: 32px auto 0;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .catalogo-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--azul);
            margin: 0;
        }
        .badge-total {
            background: var(--laranja);
            color: var(--branco);
            font-size: 0.85rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 99px;
        }

        /* ── Grid de cards ──────────────────────────────────── */
        .livros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 28px;
            max-width: 1200px;
            margin: 24px auto 60px;
            padding: 0 40px;
        }

        /* ── Card individual ────────────────────────────────── */
        .livro-card {
            background: var(--branco);
            border-radius: var(--radius);
            box-shadow: var(--sombra);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(.34,1.56,.64,1), box-shadow 0.3s;
        }
        .livro-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: var(--sombra-hov);
        }

        /* Capa */
        .capa-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 2 / 3;
            background: var(--cinza-md);
            overflow: hidden;
        }
        .capa-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .livro-card:hover .capa-wrapper img {
            transform: scale(1.05);
        }
        .capa-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15,85,178,0.55) 0%, transparent 55%);
            pointer-events: none;
        }
        .ano-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--laranja);
            color: var(--branco);
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        /* Corpo do card */
        .livro-body {
            padding: 16px 18px 0;
            flex: 1;
        }
        .livro-titulo {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--texto);
            margin: 0 0 4px;
            line-height: 1.35;
        }
        .livro-autor {
            font-size: 0.82rem;
            color: var(--azul);
            font-weight: 600;
            margin: 0;
        }

        /* Sinopse expansível */
        .sinopse-wrapper {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.45s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.35s ease,
                        padding 0.35s ease;
            opacity: 0;
            padding: 0 18px;
        }
        .sinopse-wrapper.aberta {
            max-height: 300px;
            opacity: 1;
            padding: 12px 18px 0;
        }
        .sinopse-texto {
            font-size: 0.84rem;
            line-height: 1.65;
            color: var(--texto-suave);
            border-left: 3px solid var(--laranja);
            padding-left: 12px;
        }

        /* Botão ler mais */
        .livro-footer {
            padding: 14px 18px 18px;
        }
        .btn-ler-mais {
            width: 100%;
            background: transparent;
            border: 2px solid var(--azul);
            color: var(--azul);
            padding: 8px 0;
            border-radius: 99px;
            font-family: 'Sour Gummy', cursive;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.25s, color 0.25s, border-color 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-ler-mais:hover,
        .btn-ler-mais.ativo {
            background: var(--azul);
            color: var(--branco);
            border-color: var(--azul);
        }
        .btn-ler-mais .seta {
            display: inline-block;
            transition: transform 0.3s;
            font-style: normal;
        }
        .btn-ler-mais.ativo .seta {
            transform: rotate(180deg);
        }

        /* ── Capa faltando ──────────────────────────────────── */
        .capa-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #dbe4f3 0%, #c5d4ec 100%);
            color: var(--azul);
            font-size: 0.8rem;
            font-weight: 600;
            gap: 8px;
            padding: 20px;
            text-align: center;
        }
        .capa-placeholder span { font-size: 2.5rem; }

        /* ── Responsivo ─────────────────────────────────────── */
        @media (max-width: 640px) {
            .catalogo-hero  { padding: 40px 20px 36px; }
            .catalogo-info  { padding: 0 20px; }
            .livros-grid    { padding: 0 16px; gap: 18px; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
        }
    </style>
</head>
<body id="topo">

    <!-- ═══════════════ HEADER (igual ao resto do site) ══════════════════ -->
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
                    <a href="actions/logout.php" class="btn-entrar" id="btnEntrar" title="Sair">
                        <img src="https://img.icons8.com/ios-filled/50/ff9800/login-rounded-right.png" alt="Sair">
                        <span class="btn-text">Sair</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- ═══════════════ HERO ══════════════════════════════════════════════ -->
    <section class="catalogo-hero">
        <h2>📚 Catálogo de Livros</h2>
        <p>Explore os títulos mais cobrados nos vestibulares. Clique em "Ler mais" para ver uma breve sinopse de cada obra.</p>
        <a href="leituras.php" class="btn-voltar">← Voltar para Minhas Leituras</a>
    </section>

    <!-- ═══════════════ GRID DE LIVROS ═══════════════════════════════════ -->
    <div class="catalogo-info">
        <h3>Obras disponíveis</h3>
        <span class="badge-total"><?= count($livros) ?> livros</span>
    </div>

    <section class="livros-grid">
        <?php foreach ($livros as $i => $livro): ?>
            <?php
                $caminho_capa = "capas/" . $livro['capa'];
                $tem_capa     = file_exists(__DIR__ . "/" . $caminho_capa);
            ?>
            <article class="livro-card">

                <!-- Capa -->
                <div class="capa-wrapper">
                    <?php if ($tem_capa): ?>
                        <img src="<?= htmlspecialchars($caminho_capa) ?>"
                             alt="Capa do livro <?= htmlspecialchars($livro['titulo']) ?>"
                             loading="lazy">
                        <div class="capa-overlay"></div>
                    <?php else: ?>
                        <div class="capa-placeholder">
                            <span>📖</span>
                            <?= htmlspecialchars($livro['titulo']) ?>
                        </div>
                    <?php endif; ?>
                    <span class="ano-badge"><?= intval($livro['ano']) ?></span>
                </div>

                <!-- Informações -->
                <div class="livro-body">
                    <h4 class="livro-titulo"><?= htmlspecialchars($livro['titulo']) ?></h4>
                    <p class="livro-autor"><?= htmlspecialchars($livro['autor']) ?></p>
                </div>

                <!-- Sinopse (oculta inicialmente) -->
                <div class="sinopse-wrapper" id="sinopse-<?= $i ?>">
                    <p class="sinopse-texto"><?= htmlspecialchars($livro['sinopse']) ?></p>
                </div>

                <!-- Rodapé do card -->
                <div class="livro-footer">
                    <button class="btn-ler-mais"
                            id="btn-<?= $i ?>"
                            onclick="toggleSinopse(<?= $i ?>)"
                            aria-expanded="false">
                        Ler mais <i class="seta">▾</i>
                    </button>
                </div>

            </article>
        <?php endforeach; ?>
    </section>

    <!-- ═══════════════ FOOTER ═══════════════════════════════════════════ -->
    <footer style="width:100%;background-color:#0f55b2;padding:25px 0;margin-top:auto;display:block;box-shadow:0 -2px 10px #0f55b2;">
        <div style="max-width:1200px;margin:0 auto;padding:0 50px;display:flex;justify-content:center;align-items:center;position:relative;box-sizing:border-box;">
            <span style="color:#fff;font-family:'Sour Gummy',cursive;font-size:18px;font-weight:700;text-shadow:0 2px 4px rgba(0,0,0,.1);">
                © 2026 - Todos os direitos reservados
            </span>
            <a href="#topo" style="position:absolute;right:50px;border:2px solid #fff;color:#fff;text-decoration:none;padding:8px 18px;border-radius:12px;font-family:'Sour Gummy',cursive;font-weight:600;font-size:16px;transition:.3s;box-shadow:0 4px 15px rgba(0,0,0,.1);">
                Voltar ao topo
            </a>
        </div>
    </footer>

    <script>
        function toggleSinopse(i) {
            const sinopse = document.getElementById('sinopse-' + i);
            const btn     = document.getElementById('btn-' + i);
            const aberta  = sinopse.classList.contains('aberta');

            sinopse.classList.toggle('aberta', !aberta);
            btn.classList.toggle('ativo', !aberta);
            btn.setAttribute('aria-expanded', String(!aberta));
            btn.querySelector('.seta').textContent = aberta ? '▾' : '▴';
        }
    </script>

</body>
</html>
