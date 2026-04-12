<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro | Bibliosfera</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="cadastro.css">
  <link rel="shortcut icon" href="img/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <main class="login-container" role="main">
    <h1>Bibliosfera</h1>
    <p>Crie sua conta e mergulhe no universo dos livros.</p>

  
    <form action="cad_user.php" method="post" enctype="multipart/form-data">
      <label for="nome">Nome Completo</label>
      <input name="nome" id="nome" type="text" required maxlength="100">

      <label for="email">E-mail</label>
      <input name="email" id="email" type="email" required maxlength="255">

      <label for="usuario">Usuário</label>
      <input name="usuario" id="usuario" type="text" required maxlength="50">

      <label for="senha">Senha</label>
      <input name="senha" id="senha" type="password" required maxlength="100">

      <label for="confirmarsenha">Confirmar Senha</label>
      <input name="confirmarsenha" id="confirmarsenha" type="password" required maxlength="100">

      <label for="avatar">Foto de Perfil (Opcional)</label>
      <input name="avatar" id="avatar" type="file" accept="image/jpeg, image/png, image/webp" style="margin-bottom: 12px; font-family: 'Inter', sans-serif;">

      <label for="bio">Biografia (Opcional)</label>
      <textarea name="bio" id="bio" maxlength="1000" rows="3" placeholder="Conte um pouco sobre você..." style="width: 100%; border: 1px solid rgba(15,85,178,0.2); padding: 12px; border-radius: 8px; margin-bottom: 20px; font-family: 'Inter', sans-serif;"></textarea>

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


