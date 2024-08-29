<?php
include 'conexao.php'; // Inclui o arquivo de conexão

// Verificar se a conexão foi estabelecida corretamente
if (!$conn) {
    die('Conexão não estabelecida.');
}

try {
    // Consulta para obter todos os clientes
    $sql = "SELECT id, nome, tipo_senha, senha FROM clientes";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Erro na consulta: " . $conn->error);
    }
} catch (Exception $e) {
    // Exibir a mensagem de erro diretamente para diagnóstico
    die('Erro ao consultar dados: ' . $e->getMessage());
}

// Fechar a conexão
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
        <!-- Favicon -->
    <link href="/img/att.jpg" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Painel de Clientes</h1>

    <?php
    if ($result->num_rows > 0) {
        // Início da tabela
        echo "<table>";
        echo "<tr><th>ID</th><th>Nome</th><th>Tipo de Senha</th><th>Senha</th></tr>";

        // Saída dos dados de cada linha
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["tipo_senha"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["senha"]) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum cliente encontrado.";
    }
    ?>

</body>
</html>
