<?php
header('Content-Type: application/json');

// Conectar ao banco de dados
$mysqli = new mysqli("localhost", "usuario", "senha", "nome do banco de dados");

// Verificar conexão
if ($mysqli->connect_error) {
    die("Conexão falhou: " . $mysqli->connect_error);
}

// Consulta para obter a senha atual
$query = "SELECT senha FROM tabela_senhas ORDER BY id DESC LIMIT 1";
$result = $mysqli->query($query);

$data = array();

if ($result && $row = $result->fetch_assoc()) {
    $data['senha'] = $row['senha'];
}

$mysqli->close();

// Retornar dados em JSON
echo json_encode($data);
?>
