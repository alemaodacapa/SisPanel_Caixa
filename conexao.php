<?php  
// Configurações do banco de dados  
$servername = "localhost";  
$username = "usuario de banco de dados";  
$password = "senha";  
$database = "nome do banco de dados";  

// Cria a conexão  
$conn = new mysqli($servername, $username, $password, $database);  

// Verifica a conexão  
if ($conn->connect_error) {  
    die("Falha na conexão: " . $conn->connect_error);  
}  
?>
