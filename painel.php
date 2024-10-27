<?php
// Definir informações de conexão com o banco de dados
$servidor = 'localhost'; // Altere para o servidor de banco de dados
$usuario = 'user';    // Altere para o nome de usuário do banco de dados
$senha = 'senha';       // Altere para a senha do banco de dados
$bd = 'database';  // Altere para o nome do banco de dados

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

    } else {
        // Se não houver cliente, definir valores padrão
        $cliente = [
            'senha_gerada' => '0000',
            'nome' => 'Nome do Cliente',
            'tipo_senha' => 'normal',
            'id' => 0
        ];
        $senha_anterior = 'Nenhuma senha anterior';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Atendimento - Senhas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5; /* Cor de fundo semelhante ao Facebook */
            color: #333;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .barraSuperior {
            background-color: #1877f2; /* Azul do Facebook */
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 24px;
            position: relative;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Alinha ao centro */
            padding: 20px;
            flex: 1; /* Permite que o container ocupe o espaço restante */
            overflow: auto; /* Adiciona rolagem se necessário */
        }

        .caixa {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            flex: 1 1 calc(30% - 40px); /* Tamanho flexível */
            min-width: 300px; /* Largura mínima */
            max-width: 400px; /* Largura máxima */
            text-align: center;
            opacity: 0; /* Começa invisível */
            transform: translateY(20px); /* Deslocamento inicial */
            animation: fadeInUp 0.5s forwards; /* Animação de entrada */
        }

        /* Animação de entrada */
        @keyframes fadeInUp {
            to {
                opacity: 1; /* Torna visível */
                transform: translateY(0); /* Move para a posição original */
            }
        }

        .caixa-titulo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .numero {
            font-size: 36px; /* Aumentando o tamanho da fonte dos números */
            font-weight: bold; /* Negrito para os números */
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 200px; /* Altura do vídeo */
            overflow: hidden;
            margin-top: 20px; /* Espaço acima do vídeo */
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Preenche o container */
        }

        .footer {
            background-color: #1877f2; /* Azul do Facebook */
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
        }

        /* Para garantir que o footer fique no final da página */
        .footer-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            flex: 0 0 auto;
        }
    </style>
</head>
<body>

    <div class="barraSuperior">
        ATENDIMENTO - CHAMADA POR SENHA
    </div>

    <div class="container">
        <div class="caixa">
            <div class="caixa-titulo">CAIXA</div>
            <div id="tipoSenha"><strong><?php echo strtoupper($cliente['tipo_senha']); ?></strong></div>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">ANTERIORES</div>
            <div id="senhaAnterior" class="numero"><strong><?php echo $senha_anterior; ?></strong></div>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">USUÁRIO</div>
            <div id="nomeCliente"><strong><?php echo $cliente['nome']; ?></strong></div>
            <div id="senhaGerada" class="numero"><strong><?php echo $cliente['senha_gerada']; ?></strong></div>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">ANÚNCIO</div>
            <div class="video-container">
                <video autoplay loop muted>
                    <source src="video/SEO_Summerside.mp4" type="video/mp4">
                    Seu navegador não suporta vídeo HTML5.
                </video>
            </div>
            <a href="https://painelsummerside.com.br" style="color: #1877f2;">Visitar o Site</a>
        </div>
    </div>

    <footer class="footer">
        <p>© Sis Panel - Todos os direitos reservados</p>
    </footer>

</body>
</html>
