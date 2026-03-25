<?php
session_start();
include __DIR__ . "/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nome'], $_POST['email'], $_POST['mensagem'])) {
        $_SESSION['erro_contato'] = "Erro: campos obrigatórios não preenchidos";
    } else {
        $nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $mensagem = htmlspecialchars($_POST['mensagem'], ENT_QUOTES, 'UTF-8');

        try {
            $stmt = $conn->prepare(
                "INSERT INTO msgcontato (nome_contato, email_contato, mensagem_contato) VALUES (:nome, :email, :mensagem)"
            );

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mensagem', $mensagem);

            $stmt->execute();

            $_SESSION['sucesso_contato'] = "Mensagem enviada com sucesso!";
            
        } catch (PDOException $e) {
            $_SESSION['erro_contato'] = "Erro ao enviar mensagem: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliosfera</title>
    <link rel="stylesheet"  href="style.css">
  <link rel="shortcut icon" href="img/logo.png">
    <script src="script.js"></script>
    <!-- Fontes(Google Fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Relief:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

      <header>
        <div class="dvh">
            <a href="#home" class="topo" title="Ir para o início" aria-label="Ir para o início da página">
                <img src="img/logo.png" alt="Logo da Bibliosfera" class="logo" />
                <h1 class="tituloh">Bibliosfera</h1>
            </a>
        </div>

        <nav class="nav">
            <ul class="navlinks">
                <li><a href="#quem-somos">Quem Somos</a></li>
                <li><a href="#mvv">MVV</a></li>
                <li><a href="#objetivos">Objetivos</a></li>
                <li><a href="#funcionamento">Funcionamento</a></li>
                <li><a href="#mural">Mural</a></li>
                <li><a href="#importancia">Importância</a></li>
                <li><a href="#contato">Contato</a></li>
                <li class="nav-login">
                    <a href="login.php"
                        class="btn-entrar"
                        id="btnEntrar"
                        type="button"
                        title="Entrar"
                        aria-haspopup="dialog"
                        aria-controls="loginModal"
                        aria-label="Entrar">
                        <img src="https://img.icons8.com/ios-filled/50/ff9800/login-rounded-right.png" alt="Entrar">
                        <span class="btn-text">Entrar</span>
                    </a>
                </li>
            </ul>
        </nav>
      </header>


      <div class="home-container" id="home-container">
            <section id="home" aria-label="Início da página">
                <div class="home-inner">
                    <div class="content">
                        <h3>ONDE HISTÓRIAS CRIAM CONEXÕES</h3>
                        <p>Um espaço dedicado à leitura, à troca de ideias e à construção coletiva do conhecimento por meio dos livros.</p>
                        <a href="login.php" class="btn" role="button" aria-label="Entrar na Bibliosfera" data-scroll>Entrar na Bibliosfera</a>
                    </div>
                    <img src="img/simbolo.png" alt="Símbolo da Bibliosfera" class="home-hero" loading="lazy">
                </div>
            </section>
        </div>


<div id="quem-somos" class="quem-somos" role="region" aria-label="Quem somos">
    <div class="quem-inner container">
        <figure class="quem-figure" aria-hidden="true">
            <img src="img/livros.png" alt="Livros empilhados e ambiente de leitura" loading="lazy">
        </figure>
        <div class="quem-card">
            <h3>QUEM SOMOS</h3>
            <p>
                A Bibliosfera é um clube digital que surgiu da paixão por boas histórias e da vontade de compartilhar experiências literárias com outras pessoas. Acreditamos que a leitura tem o poder de conectar, transformar e ampliar horizontes, e é exatamente isso que buscamos com nossas mídias digitais. Aqui, a leitura é coletiva. Por meio do nosso site, promovemos discussões, clubes temáticos e análises de livros em formato de vídeo, tornando a leitura acessível e envolvente para todos.
            </p>
        </div>
    </div>
</div>

<!-- MISSÃO, VISÃO E VALORES -->
<section id="mvv" class="mvv-section" role="region" aria-label="Missão, Visão e Valores">
    <div class="container">
        <h3 class="section-title">Missão, Visão e Valores</h3>
        <div class="mvv-grid" role="list" aria-label="Missão Visão Valores">
            <article class="mvv-card" role="listitem" aria-labelledby="mvv-missao">
                <h4 id="mvv-missao">MISSÃO</h4>
                <p>Acreditamos que a leitura não deve ser um privilégio, mas um direito. Por isso, promovemos o acesso a obras literárias de qualidade, de forma acessível, acolhedora e inclusiva. Mais do que entregar livros, queremos aproximar pessoas, provocar reflexões e construir uma comunidade conectada.</p>
            </article>

            <article class="mvv-card" role="listitem" aria-labelledby="mvv-visao">
                 <h4 id="mvv-visao">VISÃO</h4>
                <p>Queremos ser referência em experiências literárias coletivas, ultrapassando barreiras geográficas, culturais e sociais. Sonhamos com uma América Latina mais leitora, mais crítica, mais empática - e trabalhamos diariamente para transformar esse sonho em realidade, um livro por vez.</p>
            </article>

            <article class="mvv-card" role="listitem" aria-labelledby="mvv-valores">
                 <h4 id="mvv-valores">VALORES</h4>
                <ul class="valores-list">
                    <li>Acreditamos na leitura como fonte de conhecimento e liberdade.</li>
                    <li>Valorizamos cada voz da comunidade e o sentimento de pertencimento.</li>
                    <li>Defendemos o respeito e à diversidade, presente a inclusão e representatividade.</li>
                    <li>Cultivamos ética, empatia e colaboração em todas as nossas relações, dentro e fora da organização.</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<!-- Objetivos -->
<section id="objetivos" class="objetivos-section" role="region" aria-label="Objetivos">
  <div class="container">
    <div class="objetivos-grid">
      <article class="quem-card objetivos-card" role="article" tabindex="0">
        <h3>OBJETIVOS</h3>
        <p>O objetivo da Bibliosfera é incentivar o hábito da leitura por meio de um clube digital, promovendo a troca de experiências literárias e o contato com diferentes gêneros e obras. Busca-se criar um ambiente acessível e colaborativo, no qual leitores possam compartilhar opiniões, participar de discussões temáticas e ampliar seus conhecimentos culturais.</p>
        <p>Além disso, a Bibliosfera tem como objetivo utilizar as mídias digitais como ferramentas de aproximação entre leitores, tornando a literatura mais envolvente e contribuindo para a democratização do acesso à leitura.</p>
      </article>

      <figure class="objetivos-figure" aria-hidden="false">
        <img src="img/objetivo.png" alt="Símbolo Alvo" loading="lazy">
      </figure>
    </div>
  </div>
</section>

<!-- Funcionamento -->
<section id="funcionamento" class="funcionamento-section" role="region" aria-label="Funcionamento">
  <div class="container">
    <div class="objetivos-grid">
      <figure class="objetivos-figure funcionamento-figure" aria-hidden="false">
        <img src="img/nao-esta-funcionando.png" alt="Reunião de clube de leitura" loading="lazy">
      </figure>

      <article class="quem-card funcionamento-card" role="article" tabindex="0">
        <h3>FUNCIONAMENTO</h3>
        <p>O funcionamento da Bibliosfera ocorre por meio de um clube digital voltado à leitura coletiva. Os participantes têm acesso a discussões literárias, clubes de leitura temáticos e análises de obras, promovidas principalmente por meio do site. A proposta é incentivar a participação ativa, o diálogo entre leitores e o compartilhamento de diferentes interpretações, utilizando as mídias digitais como suporte para ampliar o acesso e o engajamento com a leitura.</p>

        <ul class="func-list" aria-hidden="false">
          <li><span class="func-icon" aria-hidden="true"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M6 2h9a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H6l-4 2V5a3 3 0 0 1 3-3z" /></svg></span><span>Clube digital com atividades regulares — discussões e análises de obras.</span></li>
          <li><span class="func-icon" aria-hidden="true"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21 6h-6l-2-3H3v18h18V6z" /></svg></span><span>Participação aberta: leitores compartilham opiniões e participam de diálogos temáticos.</span></li>
          <li><span class="func-icon" aria-hidden="true"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M3 5h18v2H3zM3 11h18v2H3zM3 17h12v2H3z"/></svg></span><span>Uso do site e mídias digitais como principal canal para conteúdo e interação.</span></li>
        </ul>
      </article>
    </div>
  </div>
</section>

<!-- MURAL DE DEPOIMENTOS -->
<section id="mural" class="mural-section" role="region" aria-label="Mural de depoimentos">
  <div class="container">
    <h3 class="section-title">Mural de Leitores</h3>

    <div class="mural-grid" role="list" aria-label="Depoimentos de leitores">
      <article class="testimonial-card" role="listitem" tabindex="0" aria-labelledby="t1-name">
        <div class="testimonial-body" aria-live="polite">
          <p>“A Bibliosfera transformou minha relação com a leitura. As discussões propostas pelo clube ampliam a forma de interpretar os livros e tornam o hábito de ler muito mais prazeroso e constante.”</p>
        </div>
        <hr>
        <div class="testimonial-meta">
          <div class="avatar" aria-hidden="true">
            <img src="img/ds.jpeg" alt="Foto de alguem" loading="lazy">
          </div>
          <div class="meta-text">
            <strong id="t1-name">Daniel Sena</strong>
            <div class="meta-sub">Pedala e Corre</div>
          </div>
        </div>
      </article>

      <article class="testimonial-card" role="listitem" tabindex="0" aria-labelledby="t2-name">
        <div class="testimonial-body">
          <p>“Participar da Bibliosfera é ter acesso a diferentes pontos de vista sobre as obras lidas, o que enriquece a experiência literária, estimula o pensamento e fortalece o interesse pela leitura coletiva.”</p>
        </div>
        <hr>
        <div class="testimonial-meta">
          <div class="avatar" aria-hidden="true">
            <img src="img/cayo.jpeg" alt="Foto de Cayo" loading="lazy">
          </div>
          <div class="meta-text">
            <strong id="t2-name">Cayo Ananias</strong>
            <div class="meta-sub">O cara da lancha</div>
          </div>
        </div>
      </article>

      <article class="testimonial-card" role="listitem" tabindex="0" aria-labelledby="t3-name">
        <div class="testimonial-body">
          <p>“O que mais me chama a atenção na Bibliosfera é a maneira como a tecnologia é utilizada para aproximar leitores. O ambiente é acolhedor, organizado e incentiva a troca de ideias de forma respeitosa.”</p>
        </div>
        <hr>
        <div class="testimonial-meta">
          <div class="avatar" aria-hidden="true">
            <img src="img/anna.jpeg" alt="Foto de Anna Luíza" loading="lazy">
          </div>
          <div class="meta-text">
            <strong id="t3-name">Anna Luíza</strong>
            <div class="meta-sub">Hater de Cayo Ananias</div>
          </div>
        </div>
      </article>
    </div>
  </div>
</section>

<br><br>
<!-- Importância -->
<section id="importancia" class="importancia-section" role="region" aria-label="Importância">
  <div class="container">
    <div class="objetivos-grid">
      <article class="quem-card importancia-card" role="article" tabindex="0">
        <h3>IMPORTÂNCIA</h3>
        <p>A Bibliosfera é importante por contribuir para o incentivo à leitura em um contexto digital cada vez mais presente no cotidiano das pessoas. Ao utilizar a tecnologia como aliada, o projeto amplia o acesso à literatura e cria oportunidades para a troca de conhecimentos e experiências entre leitores. Além disso, promove o desenvolvimento do pensamento crítico, da interação social e do interesse cultural, reforçando a leitura como uma prática acessível, colaborativa e transformadora.</p>
      </article>

      <figure class="objetivos-figure importancia-figure" aria-hidden="false">
        <img src="img/impor.webp" alt="Leitores discutindo em um espaço de leitura" loading="lazy">
      </figure>
    </div>
  </div>
</section> <br>

<!-- ESPAÇO DE CITAÇÕES (movido para cá, abaixo de Importância) -->
<section id="citacoes" class="citacoes-section" role="region" aria-label="Citações inspiradoras de autores">
  <div class="container">
    <h3 class="section-title">Citações Inspiradoras</h3>

    <div class="citacoes-grid" role="list" aria-label="Citações de autores">
      <figure class="citacao-card" role="listitem" aria-labelledby="c1-text">
        <blockquote id="c1-text" class="citacao-text">“A leitura do mundo precede a leitura da palavra.”</blockquote>
        <figcaption class="citacao-author"><strong>Paulo Freire</strong><span class="author-note"> — Pedagogia do Oprimido (1968)</span></figcaption>
      </figure>

      <figure class="citacao-card" role="listitem" aria-labelledby="c2-text">
        <blockquote id="c2-text" class="citacao-text">“Educação não transforma o mundo. Educação muda as pessoas. Pessoas transformam o mundo.”</blockquote>
        <figcaption class="citacao-author"><strong>Paulo Freire</strong><span class="author-note"> — Pedagogia da Autonomia (1996)</span></figcaption>
      </figure>

      <figure class="citacao-card" role="listitem" aria-labelledby="c3-text">
        <blockquote id="c3-text" class="citacao-text">“O livro é a melhor invenção do homem.”</blockquote>
        <figcaption class="citacao-author"><strong>Carolina Maria de Jesus</strong><span class="author-note"> — Quarto de Despejo: Diário de uma favelada (1960)</span></figcaption>
      </figure>
    </div>
  </div>
</section>

<!-- Contato -->
<section id="contato" class="contato-section" role="region" aria-label="Contato">
  <div class="container">
    <div class="objetivos-grid">
      <article class="quem-card contato-card" role="article" tabindex="0">
        <h3>CONTATO</h3>
        <p>Para mais informações sobre a Bibliosfera, sugestões ou dúvidas, entre em contato conosco por meio dos canais disponíveis. Sua participação é importante para fortalecer a comunidade e incentivar a leitura coletiva.</p>

        <div class="contato-meta" aria-hidden="false">
          <p><strong>Email:</strong> <a href="mailto:clubebibliosfera@gmail.com">clubebibliosfera@gmail.com</a></p>
        </div>

        <!-- Exibe mensagens de sucesso ao enviar o formulário -->
        <?php
          if (isset($_SESSION['sucesso_contato'])) {
            echo '<p style="color: green; font-weight: bold; margin-bottom: 15px;">' . $_SESSION['sucesso_contato'] . '</p>';
            unset($_SESSION['sucesso_contato']);
          }
        ?>

        <?php
          if (isset($_SESSION['erro_contato'])) {
            echo '<p style="color: red; font-weight: bold; margin-bottom: 15px;">' . $_SESSION['erro_contato'] . '</p>';
            unset($_SESSION['erro_contato']);
          }
        ?>

        <form class="contato-form" action="index.php#contato" method="post" aria-label="Formulário de contato">
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
          </div>
        </form>
      </article>
    </div>
  </div>
</section>

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