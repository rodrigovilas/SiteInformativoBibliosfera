<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato</title>
    <link rel="stylesheet"  href="style.css">    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>


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
                <li><a href="comunidade.html">Comunidade</a></li>
                <li><a href="perfil.php">Meu Perfil</a></li>
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




    
<section id="contato" class="contato-section" role="region" aria-label="Contato" style="margin-top: 4rem;">
  <div class="container">
    <div class="objetivos-grid">
      <article class="quem-card contato-card" role="article" tabindex="0">
        <h3>CONTATO</h3>
        <p>Para mais informações sobre a Bibliosfera, sugestões ou dúvidas, entre em contato conosco por meio dos canais disponíveis. Sua participação é importante para fortalecer a comunidade e incentivar a leitura coletiva.</p>

        <div class="contato-meta" aria-hidden="false">
          <p><strong>Email:</strong> <a href="mailto:clubebibliosfera@gmail.com">clubebibliosfera@gmail.com</a></p>
        </div>

        <form class="contato-form" action="home.html" method="post" onsubmit="alert('Mensagem enviada (simulação)'); return false;" aria-label="Formulário de contato">

          <label for="nome">Nome</label>

          <input id="nome" name="nome" type="text" placeholder="Seu nome" required>

          <label for="email-contato">E-mail</label>

          <input id="email-contato" name="email" type="email" placeholder="seu@exemplo.com" required>

          <label for="mensagem">Mensagem</label>

          <textarea id="mensagem" name="mensagem" rows="5" placeholder="Escreva sua mensagem" required></textarea>

                    <div class="contato-actions">
            <button type="submit" class="btn" aria-label="Enviar mensagem">
              <!-- ícone pequeno para decorar o botão -->
              <svg class="btn-icon" aria-hidden="true" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
              </svg>
              Enviar mensagem
            </button>
</body>
</html>
