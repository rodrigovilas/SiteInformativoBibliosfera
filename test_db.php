<?php
include 'database.php';
if ($conn) {
    echo "Conexão com o banco de dados OK!\n";
    try {
        $stmt = $conn->query("SELECT 1");
        echo "Consulta de teste OK!\n";
        
        $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Tabelas encontradas: " . implode(", ", $tables) . "\n";
    } catch (Exception $e) {
        echo "Erro na consulta: " . $e->getMessage() . "\n";
    }
} else {
    echo "Falha na conexão!\n";
}
?>
