<?php
// Arquivo de conexão com o banco de dados
$servername = "localhost";
$username = "nome de usuario de banco de dados";
$password = "senha";
$dbname = "dome do banco de dados";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Define o charset para a conexão
$conn->set_charset("utf8mb4");

// Consulta SQL para selecionar todos os registros
$sql = "SELECT id, nome, tipo_senha, senha FROM clientes";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        // Exibir os dados de todos os registros
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Tipo de Senha</th><th>Senha</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipo_senha']) . "</td>";
            echo "<td>" . htmlspecialchars($row['senha']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum registro encontrado.";
    }
    // Libera o resultado
    $result->free();
} else {
    echo "Erro ao executar a consulta: " . $conn->error;
}

// Fecha a conexão
$conn->close();
?>
