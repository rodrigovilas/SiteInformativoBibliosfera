<?php
// Arquivo de conexão com o banco de dados no Aiven usando .env

// Função para carregar o arquivo .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $linhas = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        if (strpos(trim($linha), '#') === 0) continue;
        list($nome, $valor) = explode('=', $linha, 2);
        $_ENV[trim($nome)] = trim($valor, '"\'');
    }
} else {
    die("Erro: Faltando o arquivo .env");
}

// Define o fuso horário do sistema para Brasília
date_default_timezone_set('America/Sao_Paulo');

$host = $_ENV['DB_HOST'];    
$port = $_ENV['DB_PORT'];                
$user = $_ENV['DB_USER'];                
$senha = $_ENV['DB_PASS']; 
$banco = $_ENV['DB_NAME']; 

try{
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, 
        // Define o fuso horário do banco de dados na conexão
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-03:00'"
    ];

    // Cria uma nova conexão PDO com o banco de dados MySQL
    $conn=new PDO('mysql:host='.$host.';port='.$port.';dbname='.$banco,$user,$senha,$options);
    
}catch(PDOException $e){
    // Se houver erro na conexão exibe a mensagem de erro
    echo'ERROR: '.$e->getMessage();
}
?>