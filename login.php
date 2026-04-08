<?php
// Inicia a sessão para acessar as variáveis de sessão
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <!-- Configurações meta da página de login -->
  <meta charset="UTF-8">
  <title>Login | Bibliosfera</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <link rel="shortcut icon" href="img/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <main class="login-container" role="main">
    <h1>Bibliosfera</h1>
    <p>Acesse sua conta e participe da leitura coletiva</p>

    <?php
      if (isset($_SESSION['erro_login'])) {
      echo '<p class="erro-login">' . $_SESSION['erro_login'] . '</p>';
      unset($_SESSION['erro_login']);  
      }
    ?>

    <!-- Formulário de login -->
    <!-- Os dados são enviados para login_user.php para validação -->
    <form action="login_user.php" method="post">
      <label for="email">E-mail</label>
      <input name="email" id="email" type="email" placeholder="seu@email.com" required>

      <label for="senha">Senha</label>
      <input name="senha" id="senha" type="password" placeholder="Sua senha" required>

      <button type="submit">Entrar</button>
    </form>

    <div class="login-links">
      <p>Não tem conta? <a href="cadastro.php">Criar conta</a></p>
      <!-- Link para voltar à página inicial do site -->
      <a href="index.html" class="voltar">← Voltar ao site</a>
    </div>
  </main>
</body>
</html>
