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
    die('Erro ao conectar com o banco de dados.');
}

try {
    // Obter a última senha gerada e as informações do cliente
    $sql_cliente = "SELECT c.senha AS senha_gerada, c.nome, c.tipo_senha, c.id FROM clientes c ORDER BY c.id DESC LIMIT 1";
    $result = $conn->query($sql_cliente);

    if ($result && $result->num_rows > 0) {
        // Obter os dados do cliente
        $cliente = $result->fetch_assoc();

        // Obter a última senha gerada anteriormente
        $sql_senhas_anteriores = "SELECT senha FROM clientes WHERE id < ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql_senhas_anteriores);
        $stmt->bind_param("i", $cliente['id']);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $senha_anterior = $resultado->num_rows > 0 ? $resultado->fetch_assoc()['senha'] : 'Nenhuma senha anterior';
    } else {
        // Definir valores padrão
        $cliente = ['senha_gerada' => '0000', 'nome' => 'Nome do Cliente', 'tipo_senha' => 'normal', 'id' => 0];
        $senha_anterior = 'Nenhuma senha anterior';
    }
} catch (Exception $e) {
    die('Erro ao consultar dados');
}

// Fechar a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link href="/img/att.jpg" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Gerenciador de Filas, Painel de Atendimento, Gestão de Senhas">
    <meta property="og:description" content="Sis Panel - Gerenciador de Filas Online">
    <meta property="og:image" content="https://caixa.e-painel.x10.mx/img/banner.jpg">
    <meta name="robots" content="index,follow">
    <title>Sis Panel - Gerenciador de Filas Online</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="lib/jquery-3.3.1.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/inject.js"></script>
    <script src="js/funcoes_painel.js"></script>
    <script src="js/painel.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Estilos -->
    <style>
        /* Estilo geral */
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow: hidden;
            height: 100%;
        }

        .barraSuperior {
            background-color: #003a5f;
            color: white;
            text-align: center;
            font-size: 20px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            flex: 1 0 200px;
            gap: 20px;
            overflow: hidden;
        }

        .caixa {
            width: 100%;
            max-width: 400px;
            margin: 10px 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1 1 calc(30% - 40px);
            text-align: center;
            transition: transform 0.3s;
        }

        .caixa:hover {
            transform: scale(1.05);
        }

        /* Caixa de vídeo independente */
        .caixa-video {
            background-color: transparent;
            position: relative;
            max-width: 100%;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 20px;
        }

        .caixa-video video {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        .video-overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
        }

        /* Ajuste para mobile */
        @media (max-width: 768px) {
            .caixa-video {
                width: 100%;
                max-width: 300px;
                margin: 20px auto;
            }

            .caixa-video video {
                border-radius: 10px;
                width: 100%;
            }
        }

        .footer {
            background-color: #003a5f;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="barraSuperior">ATENDIMENTO - CHAMADA POR SENHA</div>

    <div class="container">
        <div class="caixa">
            <div class="caixa-titulo">CAIXA</div>
            <h1><strong style="font-size: 33px; font-weight: bold;"><?php echo strtoupper($cliente['tipo_senha']); ?></strong></h1>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">ANTERIORES</div>
            <h1><strong style="font-size: 33px; font-weight: bold;"><?php echo $senha_anterior; ?></strong></h1>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">USUÁRIO</div>
            <h1><strong style="font-size: 33px; font-weight: bold;"><?php echo $cliente['nome']; ?></strong></h1>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">SENHA ATUAL</div>
            <h1><strong style="font-size: 50px; font-weight: bold;"><?php echo $cliente['senha_gerada']; ?></strong></h1>
        </div>

        <!-- Caixa de Relógio -->
        <div class="caixa">
            <div class="caixa-titulo">HORÁRIO</div>
            <h1>
                <div id="relogio">
                    <strong style="font-size: 33px; font-weight: bold;"></strong>
                </div>
            </h1>
        </div>

        <!-- Caixa de Previsão do Tempo -->
        <div class="caixa">
            <div class="caixa-titulo">PREVISÃO DO TEMPO</div>
            <h1 id="previsao-tempo" style="font-size: 20px;">
                Carregando previsão do tempo...
            </h1>
        </div>

        <div class="caixa-video">
            <video autoplay muted loop>
                <source src="video/SisPanel.mp4" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
            <a href="https://pay.hotmart.com/Y95202654S?checkoutMode=2" class="link-overlay"></a>
        </div>
    </div>

    <!-- Script para atualizar o relógio -->
    <script>
        function atualizarRelogio() {
            const elementoRelogio = document.getElementById("relogio").querySelector("strong");
            const agora = new Date();
            const horas = String(agora.getHours()).padStart(2, '0');
            const minutos = String(agora.getMinutes()).padStart(2, '0');
            const segundos = String(agora.getSeconds()).padStart(2, '0');
            
            elementoRelogio.textContent = `${horas}:${minutos}:${segundos}`;
        }
        setInterval(atualizarRelogio, 1000);
        atualizarRelogio();
    </script>

    <!-- Script para obter e exibir a previsão do tempo -->
    <script>
        async function obterPrevisaoTempo() {
            const apiKey = "SUA_API_KEY"; // Insira sua chave da API OpenWeatherMap aqui
            const cidade = "Sao Paulo"; // Substitua pela cidade desejada
            const url = `https://api.openweathermap.org/data/2.5/weather?q=${cidade}&appid=${apiKey}&lang=pt_br&units=metric`;

            try {
                const resposta = await fetch(url);
                const dados = await resposta.json();

                if (dados.cod === 200) {
                    const temperatura = dados.main.temp;
                    const descricao = dados.weather[0].description;
                    const cidadeNome = dados.name;

                    document.getElementById("previsao-tempo").textContent = 
                        `${cidadeNome}: ${temperatura}°C, ${descricao.charAt(0).toUpperCase() + descricao.slice(1)}`;
                } else {
                    document.getElementById("previsao-tempo").textContent = 
                        "Não foi possível obter a previsão do tempo.";
                }
            } catch (error) {
                document.getElementById("previsao-tempo").textContent = 
                    "Erro ao obter a previsão do tempo.";
            }
        }

        obterPrevisaoTempo(); // Chama a função para obter a previsão do tempo
    </script>

</body>


    <audio id="audioChamada" src="audio/chamada.wav"></audio>

    <footer class="footer">
        <p>© 2024 Sis Panel. Todos os direitos reservados. <a href="https://social.x10.mx">Social Media</a></p>
    </footer>
</body>
</html>


