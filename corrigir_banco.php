<?php
include __DIR__ . "/database.php";

try {
    $sql = "ALTER TABLE resenha MODIFY COLUMN nota decimal(3,1)";
    $conn->exec($sql);
    echo "Sucesso: Coluna 'nota' atualizada para decimal(3,1). Agora aceita o valor 10!";
} catch (PDOException $e) {
    echo "Erro ao atualizar banco: " . $e->getMessage();
}
?>
