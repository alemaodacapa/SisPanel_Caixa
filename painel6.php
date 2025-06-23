<?php
// Definir informações de conexão com o banco de dados
$servidor = 'localhost';
$usuario = 'seus dados';
$senha = 'senha';
$bd = 'seus dados';

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
    <meta name="keywords" content="Gerenciador de Filas, Painel de Atendimento, Gestão de Senhas, Senhas Preferenciais, Senhas Normais, Caixa de Atendimento">
    <meta name="robots" content="index,follow">
    <title>Sis Panel - Gerenciador de Filas Online</title>

    <link rel="icon" href="/img/att.jpg">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style2.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .barraSuperior {
            background-color: #1877f2;
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        .container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .caixa {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            min-width: 300px;
            max-width: 400px;
            text-align: center;
            animation: fadeInUp 0.5s ease-out;
        }

        .caixa:hover {
            transform: scale(1.05);
            transition: 0.3s;
        }

        .caixa-titulo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .numero {
            font-size: 36px;
            font-weight: bold;
        }

        .footer {
            background-color: #1877f2;
            color: white;
            text-align: center;
            padding: 15px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
    </div>

    <audio id="audioChamada" src="audio/chamada.wav"></audio>

    <script src="lib/jquery-3.3.1.min.js"></script>
    <script>
        function tocarAudio() {
            const audio = document.getElementById('audioChamada');
            audio.currentTime = 0;
            audio.play().catch(e => console.error('Erro ao tocar o áudio:', e));
        }

        function narrarTexto(texto) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(texto);
                speechSynthesis.speak(utterance);
            }
        }

        function atualizarDados() {
            $.get('consultar_cliente.php', function(data) {
                const cliente = JSON.parse(data);
                $('#nomeCliente').text(cliente.nome);
                $('#senhaGerada').text(cliente.senha_gerada);
                $('#tipoSenha').text(cliente.tipo_senha.toUpperCase());
                $('#senhaAnterior').text(cliente.senha_anterior);
                tocarAudio();
                narrarTexto(`Senha ${cliente.senha_gerada}, ${cliente.nome}`);
            });
        }

        $(document).ready(function() {
            atualizarDados();
            setInterval(atualizarDados, 10000);
        });
    </script>

    <footer class="footer">
        <p>&copy; 2024 Sis Panel - Todos os direitos reservados.</p>
    </footer>

</body>
</html>

