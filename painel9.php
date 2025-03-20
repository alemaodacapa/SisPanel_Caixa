<?php
// Definir informações de conexão com o banco de dados
$servidor = 'localhost';
$usuario = 'usuarios';
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
    <!-- Favicon -->
    <link href="/img/att.jpg" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Gerenciador de Filas, Painel de Atendimento, Gestão de Senhas, Senhas Preferenciais, Senhas Normais, Caixa de Atendimento, Atualização Automática, Narração de Senhas, Som de Notificação, Painel Administrativo, Relatório de Atendimento, Senhas Automáticas, Sistema de Fila Única, Interface de Atendimento, Sistema de Senhas Online, Gestão de Clientes, Atendimento ao Cliente, Acessibilidade no Atendimento, Senhas Eletrônicas, Solução de Filas"/>
    <meta property="og:description" content="Sis Panel - Gerenciador de Filas Online"/>
    <meta property="og:image" content="https://caixa.e-painel.x10.mx/img/banner.jpg" />
    <meta name="robots" content="index,follow">
    <title>Sis Panel - Gerenciador de Filas Online</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style2.css" rel="stylesheet">
    <script src="lib/jquery-3.3.1.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/inject.js"></script>
    <script src="js/funcoes_painel.js"></script>
    <script src="js/painel.js"</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '4299889786786958');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=4299889786786958&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
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
            transition: transform 0.3s; /* Transição ao passar o mouse */
        }

        /* Efeito de hover para aumentar a caixa */
        .caixa:hover {
            transform: scale(1.05); /* Aumenta o tamanho ao passar o mouse */
        }
                .campo-caixa:hover {
            transform: scale(1.05); /* Aumenta o tamanho ao passar o mouse */
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
        .row {
            margin-left: 0;
            margin-right: 0;
        }
        .col-xs-6 {
            padding-left: 10px;
            padding-right: 10px;
        }

        /* Design responsivo */
        @media (max-width: 768px) {
            .campo-caixa {
                font-size: 26px;
                padding: 20px;
            }

            .campo-caixa-usuario {
                font-size: 24px;
            }
        }

        @media (min-width: 769px) {
            .campo-caixa {
                font-size: 36px;
                padding: 35px;
        }
                .anuncio-content button {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .anuncio-content button:hover {
            background-color: #c82333;
        }

        /* Caixa de Narrador */
        .narrador-box {
            background-color: #6f42c1;
            color: white;
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 20px;
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
            background-color: #1877f2; /* Azul do Facebook */
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            position: relative;
        }

        /* Para garantir que o footer fique no final da página */
        .footer-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            flex: 0 0 auto;
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
            <a href="https://painelsummerside.com.br" class="botao-visitar" style="color: blue">Visitar o Site</a>
        </div>
    </div>
<!-- CSS para Responsividade -->
<style>
    .campo-caixa {
        background-color: #007bff; /* Mantém a cor azul */
        border-radius: 5px;
        padding: 15px;
        font-size: 30px; /* Ajuste o tamanho da fonte para mobile */
        text-align: center;
        color: white;
        margin-bottom: 20px; /* Espaçamento entre as caixas */
        overflow: hidden;
    }

    .campo-caixa-usuario {
        color: #000; /* Define a cor do texto dentro da caixa do usuário */
        font-size: 30px; /* Ajusta o tamanho do texto */
        padding: 10px; /* Ajusta o padding para manter a caixa uniforme */
    }

    @media (min-width: 992px) { /* Para desktop */
        .campo-caixa {
            font-size: 40px; /* Tamanho maior para desktop */
            padding: 20px; /* Padding maior para desktop */
        }
        
        .campo-caixa-usuario {
            font-size: 30px; /* Mantém o tamanho do texto no desktop */
        }
    }
</style>
    <!-- Áudio de chamada -->
    <audio id="audioChamada" src="audio/chamada.wav"></audio>

    <!-- JavaScript -->
    <script>
        // Função para fechar o anúncio desktop
        $('#fecharAnuncioDesktop').click(function() {
            $('#anuncioDesktop').fadeOut();
        });

        // Função para tocar o áudio de chamada
        function tocarAudio() {
            const audio = document.getElementById('audioChamada');
            audio.currentTime = 0; // Reinicia o áudio para garantir que toque do início
            audio.play().catch((error) => {
                console.error('Erro ao tentar tocar áudio:', error);
            });
        }

        // Função para buscar os dados do cliente
        function buscarDadosCliente() {
            $.ajax({
                url: 'consultar_cliente.php', // Endpoint que retorna os dados do cliente
                method: 'GET',
                success: function(data) {
                    const cliente = JSON.parse(data);
                    document.getElementById('nomeCliente').textContent = cliente.nome;
                    document.getElementById('senhaGerada').textContent = cliente.senha_gerada;
                    document.getElementById('tipoSenha').textContent = cliente.tipo_senha;
                    document.getElementById('senhaAnterior').textContent = cliente.senha_anterior;

                    // Tocar áudio se uma nova senha foi gerada
                    if (cliente.senha_gerada !== document.getElementById('senhaGerada').textContent) {
                        tocarAudio();
                    }
                },
                error: function(error) {
                    console.error('Erro ao buscar dados do cliente:', error);
                }
            });
        }

        // Atualiza os dados a cada 1 segundo
        setInterval(buscarDadosCliente, 1000);

        // Toca o áudio na primeira visita ao site
        document.addEventListener('DOMContentLoaded', function() {
            tocarAudio(); // Toca o áudio assim que o conteúdo é carregado
        });

        $(document).ready(function() {
            // Mostrar o anúncio móvel após 10 segundos
            setTimeout(function() {
                $('#anuncioMobile').fadeIn();
            }, 10000);

            // Função para fechar o anúncio móvel
            $('#fecharAnuncio').click(function() {
                $('#anuncioMobile').fadeOut();
            });
        });
    </script>

    <footer class="footer">
        <p>© Sis Panel - Todos os direitos reservados</p>
    </footer>

</body>
</html>
