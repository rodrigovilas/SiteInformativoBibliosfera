<?php
require 'database.php'; // Vai usar os dados com a sua senha do Aiven!

try {
    // Pega todo o texto do aquivo SQL
    $sql = file_get_contents('bancodedados.sql');
    
    // Roda direto no PDO
    $conn->exec($sql);
    
    echo "<div style='font-family: Arial; padding: 20px; text-align: center;'>";
    echo "<h1 style='color: green;'>Sucesso Absoluto! 🎉</h1>";
    echo "<p>O seu site acabou de criar todas as tabelas e importar os livros lá no servidor online do Aiven!</p>";
    echo "<a href='home.html'>Voltar para a Home</a>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='font-family: Arial; padding: 20px; text-align: center;'>";
    echo "<h1 style='color: red;'>Ops! Ocorreu um erro.</h1>";
    echo "<b>Detalhes do erro: </b>" . $e->getMessage() . "<br><br>";
    echo "Lembre-se de verificar se você colocou sua senha corretamente no arquivo <code>database.php</code>!";
    echo "</div>";
}
?>
