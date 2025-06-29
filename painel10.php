<?php
// Configurações do banco de dados
$servidor = 'localhost';
$usuario = 'usuario';
$senha = 'senha';
$bd = 'seusdados';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Caixa</title>
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
        @media (max-width: 767px) {
            .col-xs-6 {
                width: 100%;
                padding-left: 0;
                padding-right: 0;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
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

        @media (min-width: 992px) and (max-width: 1199px) {
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

        @media (min-width: 1200px) {
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
    <div class="barraSuperior">
        <div class="row">
            <div class="col-xs-1">
                <img src="img/att.jpg" class="uespiLogo" alt="Logo">
            </div>
            <div class="col-xs-11 text-right">
                <span class="uespiTexto" style="color: white;">ATENDIMENTO</span><br>
                <span class="subtitulo">Chamada <strong>por Senha</strong></span><br>
                <a href="https://social.x10.mx" class="info-link">
                    <i class="fa fa-info-circle"></i> Info
                </a>
            </div>
        </div>
    </div>
    
    <div class="container page">
        <div class="row">
            <div class="col-xs-6">
                <div class="caixa-normal">
                    <div><strong>CAIXA</strong></div>
                    <div><strong id="tipoSenha"><?php echo strtoupper($cliente['tipo_senha']); ?></strong></div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="caixa-anterior">
                    <div><strong>ANTERIORES</strong></div>
                    <div id="senhaAnterior"><?php echo $senha_anterior; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="campo-caixa">
                    <div><strong>USUÁRIO</strong></div>
                    <div class="campo-caixa-usuario">
                        <span id="nomeCliente"><?php echo $cliente['nome']; ?></span><br>
                        <div id="info">
                            <span id="senhaGerada" style="font-size: 40px; font-weight: bold;"><?php echo $cliente['senha_gerada']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio id="audioChamada" src="audio/chamada.wav"></audio>

    <div class="footer">
        <p><a href="https://social.x10.mx">Sis Panel</a> Todos os direitos reservados</p>
    </div>
</body>
</html>
