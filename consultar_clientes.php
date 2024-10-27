<?php
// Arquivo de conexão com o banco de dados
$servername = "localhost";
$username = "nome de usuario de banco de dados";
$password = "senha";
$dbname = "dome do banco de dados";

// Criar conexão com o banco de dados
$conn = new mysqli($servidor, $usuario, $senha, $bd);

// Verificar se a conexão foi estabelecida corretamente
if ($conn->connect_error) {
    die('Conexão não estabelecida: ' . $conn->connect_error);
}

try {
    // Obter a última senha gerada e as informações do cliente
    $sql_cliente = "
        SELECT 
            c.senha AS senha_gerada, 
            c.nome, 
            c.tipo_senha,
            c.id
        FROM clientes c
        ORDER BY c.id DESC 
        LIMIT 1
    ";
    
    $result = $conn->query($sql_cliente);

    if ($result->num_rows > 0) {
        // Obter os dados do cliente
        $cliente = $result->fetch_assoc();

        // Obter a última senha gerada anteriormente
        $sql_senhas_anteriores = "
            SELECT senha 
            FROM clientes 
            WHERE id < ? 
            ORDER BY id DESC 
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql_senhas_anteriores);
        $stmt->bind_param("i", $cliente['id']);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $senha_anterior = $resultado->num_rows > 0 ? $resultado->fetch_assoc()['senha'] : 'Nenhuma senha anterior';

        // Retornar os dados em formato JSON
        echo json_encode([
            'nome' => $cliente['nome'],
            'senha_gerada' => $cliente['senha_gerada'],
            'tipo_senha' => $cliente['tipo_senha'],
            'senha_anterior' => $senha_anterior
        ]);
    } else {
        // Se não houver cliente, retornar valores padrão
        echo json_encode([
            'nome' => 'Nome do Cliente',
            'senha_gerada' => '0000',
            'tipo_senha' => 'normal',
            'senha_anterior' => 'Nenhuma senha anterior'
        ]);
    }
} catch (Exception $e) {
    die('Erro ao consultar dados: ' . $e->getMessage());
}

// Fechar a conexão
$conn->close();
?>

