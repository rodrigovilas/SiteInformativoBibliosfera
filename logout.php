<?php
// Arquivo de logout (desconexão)
// Encerra a sessão do usuário e redireciona para a página inicial

session_start();       // Inicia a sessão existente
session_destroy();     // Destroi a sessão, removendo todos os dados do usuário
header('Location: index.php');  // Redireciona para a página inicial do site
?>