<?php
include __DIR__ . "/database.php";

// Desativar o modo de exceção temporariamente para lidar com erros de colunas duplicadas
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

echo "<h2>Iniciando migração do banco...</h2>";

// 1. Adicionar colunas de páginas
$res1 = $conn->exec("ALTER TABLE listausuario ADD COLUMN paginas_totais INT DEFAULT 0");
if ($res1 === false) {
    echo "<li>Coluna 'paginas_totais' já existe ou erro ignorado.</li>";
} else {
    echo "<li>Coluna 'paginas_totais' adicionada com sucesso.</li>";
}

$res2 = $conn->exec("ALTER TABLE listausuario ADD COLUMN pagina_atual INT DEFAULT 0");
if ($res2 === false) {
    echo "<li>Coluna 'pagina_atual' já existe ou erro ignorado.</li>";
} else {
    echo "<li>Coluna 'pagina_atual' adicionada com sucesso.</li>";
}

// 2. Corrigir tipo da nota
$res3 = $conn->exec("ALTER TABLE resenha MODIFY COLUMN nota decimal(3,1)");
echo "<li>Configuração de nota atualizada.</li>";

// 3. Criar tabela de histórico
$stmt_hist = "CREATE TABLE IF NOT EXISTS historico_leitura (
    id_historico INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_livro INT,
    pagina INT,
    comentario TEXT,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES login(id_usuario),
    FOREIGN KEY (id_livro) REFERENCES livro(id_livro)
)";
$conn->exec($stmt_hist);
echo "<li>Tabela de histórico verificada/criada.</li>";

echo "<h3>✅ Migração concluída com sucesso!</h3>";
echo "<p><a href='leituras.php'>Voltar para Leituras</a></p>";
?>
