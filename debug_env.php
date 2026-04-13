<?php
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $linhas = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '' || strpos($linha, '#') === 0) continue;
        if (strpos($linha, '=') !== false) {
            list($nome, $valor) = explode('=', $linha, 2);
            $nome = trim($nome);
            $valor = trim(trim($valor), '"\'');
            echo "Variable [$nome] = [$valor]\n";
        }
    }
}
?>
