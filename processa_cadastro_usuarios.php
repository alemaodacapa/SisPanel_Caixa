<?php
include 'conexao.php';

// Verifica se a conexão foi estabelecida
if (!$conn) {
    die('Conexão não estabelecida.');
}

// Obtém os dados enviados via POST
$nome = $_POST['nome'];
$tipo_senha = $_POST['tipo_senha'];
$senha = $_POST['senha'];

// Insere os dados no banco de dados
$sql = "INSERT INTO clientes (nome, tipo_senha, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $tipo_senha, $senha);

if ($stmt->execute()) {
    echo 'Cadastro realizado com sucesso!';
} else {
    echo 'Erro ao cadastrar o cliente: ' . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>
