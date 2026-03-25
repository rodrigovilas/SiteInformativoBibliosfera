<?php
// Arquivo de conexão com o banco de dados
// Define os parâmetros de acesso ao MySQL

$host="localhost";           // Servidor MySQL (máquina local)
$port="3306";                // Porta padrão do MySQL
$user="root";                // Usuário do MySQL
$senha="root";               // Senha do usuário MySQL
$banco="bibliosfera";        // Nome do banco de dados

try{
    // Cria uma nova conexão PDO com o banco de dados MySQL
    $conn=new PDO('mysql:host='.$host.';port='.$port.';dbname='.$banco,$user,$senha);
    // Define o modo de erro para lançar exceções (para melhor tratamento de erros)
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    // Se houver erro na conexão, exibe a mensagem de erro
    echo'ERROR: '.$e->getMessage();
}
?>