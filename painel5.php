<?php
// Definir informações de conexão com o banco de dados
$servidor = 'localhost';
$usuario = 'usuario';
$senha = 'sua senha';
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
    <!-- Favicon -->
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #ffffff;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .barraSuperior {
            background-color: #0056b3;
            padding: 20px;
            color: white;
            height: 200px;
            margin-bottom: 20px;
            position: relative;
        }

        .uespiLogo {
            height: 80px;
        }

        .uespiTexto {
            font-size: 24px;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 18px;
        }

        .container.page {
            padding: 1px;
        }

        .campo-caixa {
            background-color: #007bff;
            border-radius: 5px;
            padding: 15px;
            font-size: 40px;
            text-align: center;
            color: white;
            margin-bottom: 20px; /* Espaçamento entre as caixas */
            overflow: hidden;
        }

        .campo-caixa-usuario {
            background-color: #ffff00;
            color: #000000;
            font-size: 30px;
            padding: 15px;
            font-weight: bold;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        .col-xs-6 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .caixa-normal,
        .caixa-anterior {
            background-color: #0056b3;
            border-radius: 5px;
            padding: 20px;
            font-size: 40px;
            text-align: center;
            color: white;
            margin-bottom: 20px; /* Espaçamento entre as caixas */
            overflow: hidden;
        }

        /* Design responsivo */
        @media (max-width: 400px) {
            .col-xs-6 {
                width: 100%;
                padding-left: 0;
                padding-right: 0;
            }
        }

        @media (min-width: 400px) and (max-width: 991px) {
            .campo-caixa,
            .caixa-normal,
            .caixa-anterior {
                font-size: 30px;
                padding: 15px;
            }

            .campo-caixa-usuario {
                font-size: 20px;
            }
        }

        @media (min-width: 400px) and (max-width: 400px) {
            .campo-caixa,
            .caixa-normal,
            .caixa-anterior {
                font-size: 35px;
                padding: 18px;
            }

            .campo-caixa-usuario {
                font-size: 25px;
            }
        }

        @media (min-width: 400px) {
            .campo-caixa,
            .caixa-normal,
            .caixa-anterior {
                font-size: 40px;
                padding: 20px;
            }

            .campo-caixa-usuario {
                font-size: 30px;
            }
        }

        .info-link {
            display: inline-flex;
            align-items: center;
            margin-left: 10px;
            font-size: 16px;
        }

        .info-link i {
            margin-right: 5px;
            font-size: 20px;
            color: #007bff;
        }

        .footer {
            background-color: #0056b3;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .footer a {
            color: #ffff00;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // Função para tocar o áudio
        function tocarAudio() {
            const audio = document.getElementById('audioChamada');
            audio.play();
        }

        // Função para narrar texto
        function narrarTexto(texto) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(texto);
                speechSynthesis.speak(utterance);
            } else {
                alert('Navegador não suporta síntese de fala.');
            }
        }

        // Função para narrar o nome do cliente e a senha gerada ao carregar a página
        function narrarInformacoes() {
            const nomeCliente = document.getElementById('nomeCliente').textContent;
            const senhaGerada = document.getElementById('senhaGerada').textContent;
            narrarTexto(`Senha gerada para ${nomeCliente} é ${senhaGerada}`);
        }

        // Executa as funções quando a página é carregada
        window.onload = function() {
            tocarAudio(); // Toca o áudio
            narrarInformacoes();
            
            // Atualiza a página a cada 25 segundos
            setInterval(function() {
                location.reload();
            }, 25000); // 25000 milissegundos = 25 segundos
        };
    </script>
</head>
<body>
<body>
    <div class="barraSuperior">
        <img src="img/att.jpg" class="uespiLogo" alt="Logo">
        <div>
            <div class="uespiTexto">ATENDIMENTO</div>
            <div class="subtitulo">Chamada por Senha</div>
        </div>
    </div>

    <div class="container page">
        <div class="row">
            <div class="col-md-6">
                <div class="campo-caixa">
                    <strong>CAIXA</strong>
                    <div id="tipoSenha"><?php echo strtoupper($cliente['tipo_senha']); ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="campo-caixa">
                    <strong>ANTERIORES</strong>
                    <div id="senhaAnterior"><?php echo $senha_anterior; ?></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="campo-caixa">
                    <strong>USUÁRIO</strong>
                    <div class="campo-caixa-usuario">
                        <span id="nomeCliente"><?php echo $cliente['nome']; ?></span><br>
                        <span id="senhaGerada"><?php echo $cliente['senha_gerada']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio id="audioChamada" src="audio/chamada.wav"></audio>

    <div class="footer">
        <p><a href="https://social.x10.mx/templates.html">Sis Panel</a> Todos os direitos reservados</p>
    </div>
</body>
</html>
