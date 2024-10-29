<?php
// Definir informações de conexão com o banco de dados
$servidor = 'localhost';
$usuario = 'usuario';
$senha = 'senha';
$bd = 'database';

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
    <link href="/img/att.jpg" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Gerenciador de Filas, Painel de Atendimento, Gestão de Senhas, Senhas Preferenciais, Senhas Normais, Caixa de Atendimento, Atualização Automática, Narração de Senhas, Som de Notificação, Painel Administrativo, Relatório de Atendimento, Senhas Automáticas, Sistema de Fila Única, Interface de Atendimento, Sistema de Senhas Online, Gestão de Clientes, Atendimento ao Cliente, Acessibilidade no Atendimento, Senhas Eletrônicas, Solução de Filas"/>
    <meta property="og:description" content="Sis Panel - Gerenciador de Filas Online"/>
    <meta property="og:image" content="https://caixa.e-painel.x10.mx/img/banner.jpg" />
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
        }

        /* Estilos da Barra Superior */
        .barraSuperior {
            background-color: #003a5f;
            color: white;
            text-align: center;
            font-size: 24px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100px;
        }

        /* Container Principal */
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Centraliza as caixas */
            padding: 20px;
            flex: 1;
            gap: 20px; /* Espaço entre as caixas */
            overflow: auto;
        }

        /* Estilo das Caixas */
        .caixa {
            width: 100%; /* Faz as caixas ocuparem 100% da largura */
            max-width: 350px; /* Define uma largura máxima para as caixas */
            margin: 10px; /* Margem para espaçamento */
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px; /* Maior padding para melhor visualização */
            text-align: center;
            transition: transform 0.3s;
        }

        .caixa:hover {
            transform: scale(1.05); /* Efeito de hover */
        }

        /* Ajustes para mobile */
        @media (max-width: 768px) {
            .caixa {
                flex: 1 1 100%; /* Cada caixa ocupa 100% da largura */
                max-width: 100%;
                border-radius: 20px; /* Bordas arredondadas */
            }
        }

        /* Caixa de vídeo */
        .caixa-video {
            background-color: transparent;
            position: relative;
            max-width: 100%; /* Ajuste da largura */
            margin: auto; /* Centraliza a caixa na tela */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 20px;
        }

        /* Estilo do vídeo */
        .caixa-video video {
            width: 100%; /* Ajusta a largura do vídeo */
            height: auto;
            object-fit: cover; /* Cobre a área da caixa */
            border-radius: 10px;
        }

        /* Link invisível sobre o vídeo */
        .video-overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
        }

        /* Footer */
        .footer {
            background-color: #003a5f;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            font-size: 14px;
        }

        .footer a {
            color: #ffff00; /* Cor do link */
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline; /* Sublinha ao passar o mouse */
        }

        /* Estilo dos campos */
        .campo-caixa {
            background-color: #007bff;
            border-radius: 5px;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .campo-caixa-usuario {
            color: #000;
            font-size: 24px;
            padding: 10px;
        }
    </style>
</head>
<body>

    <!-- Barra Superior -->
    <div class="barraSuperior">
        ATENDIMENTO - CHAMADA POR SENHA
    </div>

    <!-- Contêiner principal das caixas -->
    <div class="container">
        <div class="caixa">
            <div class="caixa-titulo">CAIXA</div>
            <h1>
                <div id="tipoSenha">
                    <strong style="font-size: 33px; font-weight: bold;"><?php echo strtoupper($cliente['tipo_senha']); ?></strong>
                </div>
            </h1>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">ANTERIORES</div>
            <h1>
                <div id="senhaAnterior" class="numero">
                    <strong style="font-size: 33px; font-weight: bold;"><?php echo $senha_anterior; ?></strong>
                </div>
            </h1>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">USUÁRIO</div>
            <h1>
                <div id="nomeCliente">
                    <strong style="font-size: 33px; font-weight: bold;"><?php echo $cliente['nome']; ?></strong>
                </div>
            </h1>
        </div>
        <div class="caixa">
            <div class="caixa-titulo">SENHA GERADA</div>
            <h1>
                <div id="senhaGerada" class="numero">
                    <strong style="font-size: 50px; font-weight: bold;"><?php echo $cliente['senha_gerada']; ?></strong>
                </div>
            </h1>
        </div>
        <div class="caixa-video">
            <video autoplay muted loop>
                <source src="video/SisPanel.mp4" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
            <a href="https://pay.hotmart.com/Y95202654S?checkoutMode=2" class="link-overlay"></a>
            <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
            </div>
        </div>
    </div>

    <audio id="audioChamada" src="audio/chamada.mp3"></audio>
    
    <footer class="footer">
        <p>© 2024 Sis Panel. Todos os direitos reservados. | <a href="https://social.x10.mx">Social Media</a></p>
    </footer>
</body>
</html>
