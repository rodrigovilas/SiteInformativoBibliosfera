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
            // 1. Salva no Banco de Dados
            $stmt = $conn->prepare(
                "INSERT INTO msgcontato (nome_contato, email_contato, mensagem_contato) VALUES (:nome, :email, :mensagem)"
            );

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mensagem', $mensagem);
            $stmt->execute();

            // 2. Redireciona os dados VISUALMENTE para o FormSubmit (Front-end)
            // Isso garante que o FormSubmit vai reconhecer você como um humano e exibirá a página "Activate"
            echo '<!DOCTYPE html><html><body style="background-color: #0f55b2; color: white; font-family: Arial; text-align: center; padding-top: 50px;">';
            echo '<h2>Enviando para o servidor de e-mail...</h2>';
            
            // Formulário fantasma configurado para o FormSubmit
            echo '<form id="form_redirecionamento" action="https://formsubmit.co/clubebibliosfera@gmail.com" method="POST">';
            echo '<input type="hidden" name="Nome" value="' . $nome . '">';
            echo '<input type="hidden" name="Email_do_Visitante" value="' . $email . '">';
            echo '<input type="hidden" name="Mensagem" value="' . $mensagem . '">';
            echo '<input type="hidden" name="_subject" value="Novo Contato - Bibliosfera">';
            echo '<input type="hidden" name="_replyto" value="' . $email . '">';
            
            // Onde mandar o usuário depois de enviar o email
            echo '<input type="hidden" name="_next" value="http://localhost/SiteInformativoBibliosfera/contato.php?sucesso=true">';
            echo '</form>';
            
            // O Javascript aperta o botão de enviar automaticamente
            echo '<script>document.getElementById("form_redirecionamento").submit();</script>';
            echo '</body></html>';
            exit; // Interrompe para não carregar a página de contato embaixo

        } catch (PDOException $e) {
            $_SESSION['erro_contato'] = "Erro ao processar mensagem: " . $e->getMessage();
        }
    }
}

// Verifica se veio do redirecionamento do FormSubmit
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'true') {
    $_SESSION['sucesso_contato'] = "Mensagem enviada com sucesso! Recebemos seu E-mail.";
    // Limpa a URL pra ficar bonita
    header("Location: contato.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato</title>
    <link rel="stylesheet"  href="style.css"> 
    <link rel="shortcut icon" href="img/logo.png">   
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
                <li><a href="comunidade.php">Comunidade</a></li>
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

        <form class="contato-form" action="contato.php" method="post" aria-label="Formulário de contato">
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
