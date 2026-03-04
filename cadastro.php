<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login | Bibliosfera</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="cadastro.css">
  <link rel="shortcut icon" href="img/logo.png">
  <!-- Fontes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">

</head>
<body>

  <main class="login-container" role="main">
    <h1>Bibliosfera</h1>
    <p>Crie sua conta e mergulhe no universo dos livros.</p>

    <form action="cad_user.php" method="post">
      <label for="email">E-mail</label>
      <input name="email" id="email" type="email" required maxlength="255">

      <label for="usuario">Usuário</label>
      <input name="usuario" id="usuario" type="text" required maxlength="50">

      <label for="senha">Senha</label>
      <input name="senha" id="senha" type="password" required maxlength="100">

      <label for="confirmarsenha">Confirmar Senha</label>
      <input name="confirmarsenha" id="confirmarsenha" type="password" required maxlength="100">

      <button type="submit">Criar Conta</button>

        <?php
      if (isset($_SESSION['erro_login'])) {
      echo '<p class="erro-login">' . $_SESSION['erro_login'] . '</p>';
      unset($_SESSION['erro_login']);
      }
    ?>


    </form>


    <div class="login-links">
      <a href="login.php" class="voltar">← Voltar ao login</a>
    </div>
  </main>
</body>
</html>


