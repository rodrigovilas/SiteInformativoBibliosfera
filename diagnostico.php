<?php
// diagnostico.php - Ferramenta de solução de problemas para ambiente e banco de dados

// Tenta carregar o .env e ver se as variáveis existem
$envFile = __DIR__ . '/.env';
$envExists = file_exists($envFile);
$varsFound = [];
$varsMissing = [];

if ($envExists) {
    $linhas = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '' || strpos($linha, '#') === 0) continue;
        if (strpos($linha, '=') !== false) {
            list($nome, $valor) = explode('=', $linha, 2);
            $_ENV[trim($nome)] = trim(trim($valor), '"\'');
        }
    }

    $required = ['DB_HOST', 'DB_PORT', 'DB_USER', 'DB_PASS', 'DB_NAME'];
    foreach ($required as $v) {
        if (isset($_ENV[$v])) {
            $varsFound[] = $v;
        } else {
            $varsMissing[] = $v;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Diagnóstico de Ambiente - Bibliosfera</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; max-width: 800px; margin: 40px auto; padding: 20px; background: #f4f7f6; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .warning { color: #f39c12; font-weight: bold; }
        pre { background: #eee; padding: 10px; border-radius: 4px; overflow-x: auto; }
        h1 { color: #2c3e50; }
        .step { margin-bottom: 15px; border-left: 4px solid #3498db; padding-left: 15px; }
    </style>
</head>
<body>
    <h1>Diagnóstico de Ambiente - Bibliosfera</h1>
    
    <div class="card">
        <h2>1. Verificação do Arquivo .env</h2>
        <div class="step">
            Status do arquivo: 
            <?php if ($envExists): ?>
                <span class="success">Encontrado!</span>
            <?php else: ?>
                <span class="error">Não encontrado!</span>
                <p>Crie um arquivo chamado <code>.env</code> na raiz do projeto.</p>
            <?php endif; ?>
        </div>

        <?php if ($envExists): ?>
            <div class="step">
                Variáveis obrigatórias:
                <ul>
                    <?php foreach ($required as $v): ?>
                        <li>
                            <?php echo $v; ?>: 
                            <?php if (in_array($v, $varsFound)): ?>
                                <span class="success">OK</span>
                            <?php else: ?>
                                <span class="error">Faltando</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>2. Teste de Conexão com o Banco de Dados</h2>
        <?php if ($envExists && empty($varsMissing)): ?>
            <?php
            try {
                $host = $_ENV['DB_HOST'];
                $port = $_ENV['DB_PORT'];
                $user = $_ENV['DB_USER'];
                $pass = $_ENV['DB_PASS'];
                $db   = $_ENV['DB_NAME'];

                echo "<p>Tentando conectar a <code>$host</code>...</p>";
                
                $start = microtime(true);
                $conn = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                    PDO::ATTR_TIMEOUT => 5
                ]);
                $end = microtime(true);
                $time = round($end - $start, 2);

                echo "<p class='success'>Conexão estabelecida com sucesso em $time segundos!</p>";
                
                $stmt = $conn->query("SELECT 1");
                echo "<p class='success'>Consulta de teste OK!</p>";

                $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                echo "<p>Tabelas encontradas: " . (empty($tables) ? "Nenhuma" : implode(", ", $tables)) . "</p>";
                
                if (!in_array('login', $tables)) {
                    echo "<p class='warning'>Aviso: A tabela 'login' não foi encontrada. Você importou o arquivo bancodedados.sql?</p>";
                }

            } catch (PDOException $e) {
                echo "<div class='error'>";
                echo "<h3>Falha na Conexão!</h3>";
                echo "<p>Mensagem: " . $e->getMessage() . "</p>";
                echo "</div>";

                if (strpos($e->getMessage(), 'getaddrinfo failed') !== false) {
                    echo "<div class='card' style='background: #fff5f5; border: 1px solid #feb2b2;'>";
                    echo "<h4>Dica de Solução: Host Desconhecido</h4>";
                    echo "<p>O computador não conseguiu encontrar o endereço do servidor na internet.</p>";
                    echo "<ul>";
                    echo "<li>Verifique se você está conectado à internet.</li>";
                    echo "<li>Confirme se o <code>DB_HOST</code> no arquivo <code>.env</code> está escrito corretamente (sem espaços ou aspas extras).</li>";
                    echo "<li>Tente usar um DNS diferente (como o do Google: 8.8.8.8) nas configurações do seu Windows.</li>";
                    echo "</ul>";
                    echo "</div>";
                }
            }
            ?>
        <?php else: ?>
            <p class="warning">Resolva os problemas do passo 1 primeiro.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>3. Verificação de Pastas</h2>
        <div class="step">
            Pasta <code>uploads/avatars/</code>: 
            <?php 
            $path = __DIR__ . '/uploads/avatars/';
            if (is_dir($path)) {
                echo is_writable($path) ? "<span class='success'>OK (Existe e tem permissão)</span>" : "<span class='warning'>Existe, mas sem permissão de escrita!</span>";
            } else {
                echo "<span class='error'>Não existe!</span> (Ela será criada automaticamente no primeiro cadastro).";
            }
            ?>
        </div>
    </div>

    <p style="text-align: center; color: #7f8c8d;">Bibliosfera - Ferramenta de Diagnóstico</p>
</body>
</html>
