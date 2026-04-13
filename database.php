<?php
// Arquivo de conexão com o banco de dados no Aiven usando .env

// Função para carregar o arquivo .env de forma robusta
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $linhas = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '' || strpos($linha, '#') === 0) continue;
        
        if (strpos($linha, '=') !== false) {
            list($nome, $valor) = explode('=', $linha, 2);
            $_ENV[trim($nome)] = trim(trim($valor), '"\'');
        }
    }
} else {
    die("Erro Crítico: Arquivo .env não encontrado. Por favor, crie um arquivo .env baseado no .env.example");
}

// Define o fuso horário do sistema para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Verifica se todas as variáveis necessárias existem
$required_vars = ['DB_HOST', 'DB_PORT', 'DB_USER', 'DB_PASS', 'DB_NAME'];
foreach ($required_vars as $var) {
    if (!isset($_ENV[$var])) {
        die("Erro Crítico: A variável de ambiente $var não está definida no arquivo .env");
    }
}

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
    $conn = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$banco, $user, $senha, $options);
    
}catch(PDOException $e){
    // Se houver erro na conexão, mata o script com uma mensagem clara para o usuário
    die("<h3>Erro de Conexão com o Banco de Dados</h3>" . 
        "<p>Não foi possível conectar ao banco de dados na nuvem.</p>" .
        "<p><strong>Detalhes do erro:</strong> " . $e->getMessage() . "</p>" .
        "<p><em>Dica: Verifique se sua internet está funcionando e se os dados no .env estão corretos.</em></p>");
}

?>