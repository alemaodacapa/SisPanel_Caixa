<?php
// Definir informações de conexão com o banco de dados
$servidor = 'localhost';
$usuario = 'usuario aqui';
$senha = "sua senha aqui';
$bd = 'owjlmvae_caixa';

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
    <script src="js/inject.js"></script>
    <script src="js/funcoes_painel.js"></script>
    <script src="js/painel.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            background-color: #e9ecef;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .barraSuperior {
            background-color: #6c757d;
            padding: 20px;
            color: white;
            height: 200px;
            display: flex;
            align-items: center;
        }

        .uespiLogo {
            height: 80px;
            margin-right: 20px;
        }

        .uespiTexto {
            font-size: 28px;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 18px;
            font-style: italic;
        }

        .container.page {
            padding: 20px;
        }

        .campo-caixa {
            background-color: #17a2b8;
            border-radius: 15px;
            padding: 30px;
            font-size: 32px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }

        .campo-caixa:hover {
            transform: scale(1.05); /* Aumenta o tamanho ao passar o mouse */
        }

        .campo-caixa-usuario {
            background-color: #ffc107;
            color: #000;
            font-size: 32px;
            padding: 20px;
            font-weight: bold;
            border-radius: 15px;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
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

            .campo-caixa-usuario {
                font-size: 30px;
            }
        }

        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 15px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .footer a {
            color: #ffc107;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Anúncios */
        .anuncio-content {
            background-color: #28a745;
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-top: 20px; /* Adiciona margem superior */
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
    </style>
</head>
<body>
    <div class="barraSuperior">
        <img src="img/att.jpg" class="uespiLogo" alt="Logo">
        <div>
            <span class="uespiTexto">ATENDIMENTO</span><br>
            <span class="subtitulo">Chamada <strong>por Senha</strong></span>
            <a href="https://social.x10.mx" class="info-link">
                <i class="fa fa-info-circle"></i> Info
            </a>
        </div>
    </div>

    <div class="container page">
        <div class="row">
            <div class="col-md-6">
                <div class="campo-caixa">
                    <div><strong>CAIXA</strong></div>
                    <div><strong id="tipoSenha"><?php echo strtoupper($cliente['tipo_senha']); ?></strong></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="campo-caixa">
                    <div><strong>ANTERIORES</strong></div>
                    <div id="senhaAnterior"><?php echo $senha_anterior; ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="campo-caixa">
                    <div><strong>USUÁRIO</strong></div>
                    <div class="campo-caixa-usuario">
                        <span id="nomeCliente"><?php echo $cliente['nome']; ?></span><br>
                        <div id="info">
                            <span id="senhaGerada" style="font-size: 44px; font-weight: bold;">
                                <?php echo $cliente['senha_gerada']; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Caixa do Narrador -->
            <div class="col-md-6">
                <div class="narrador-box">
                    <strong>NARRADOR</strong>
                    <div id="narracao">
                        <span id="narracaoTexto">Aguarde, sua senha será chamada em breve.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Anúncio para Desktop -->
        <div class="col-md-6 d-none d-md-block">
            <div class="anuncio-content">
                <strong>ANÚNCIO</strong>
                <div class="video-container">
                    <video id="seoVideoDesktop" width="100%" autoplay loop muted>
                        <source src="video/SEO_Summerside.mp4" type="video/mp4">
                        Seu navegador não suporta vídeo HTML5.
                    </video>
                </div>
                <a href="https://painelsummerside.com.br" class="botao-visitar-desktop" style="color: white">Visitar o Site</a>
            </div>
        </div>

        <!-- Anúncio para Mobile -->
        <div class="col-md-6 d-md-none">
            <div class="anuncio-content" style="display: none;">
                <button id="fecharAnuncio">X</button>
                <div>
                    <strong>ANÚNCIO</strong>
                    <div class="video-container">
                        <video id="seoVideoMobile" width="100%" autoplay loop muted>
                            <source src="video/SEO_Summerside.mp4" type="video/mp4">
                            Seu navegador não suporta vídeo HTML5.
                        </video>
                    </div>
                    <a href="https://painelsummerside.com.br" class="botao-visitar" style="color: white">Visitar o Site</a>
                </div>
            </div>
        </div>

        <!-- Seção de anúncios agora abaixo da caixa de narrador -->
        <div class="row">
            <div class="col-12">
                <div class="anuncio-content" style="display: none;">
                    <button id="fecharAnuncio">X</button>
                    <strong>ANÚNCIO</strong>
                    <div class="video-container">
                        <video id="seoVideoDesktop" width="100%" autoplay loop muted>
                            <source src="video/SEO_Summerside.mp4" type="video/mp4">
                            Seu navegador não suporta vídeo HTML5.
                        </video>
                    </div>
                    <a href="https://painelsummerside.com.br" class="botao-visitar-desktop" style="color: white">Visitar o Site</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para tocar o áudio
        function tocarAudio() {
            const audio = new Audio('path/to/audio/file.mp3'); // Substitua pelo caminho do seu áudio
            audio.play().catch((error) => {
                console.error("Erro ao tocar áudio: ", error);
            });
        }

        // Função para buscar dados do cliente
        function buscarDadosCliente() {
            $.ajax({
                url: 'http://seu_backend/api/dados_cliente',
                method: 'GET',
                success: function(cliente) {
                    document.getElementById('nomeCliente').textContent = cliente.nome;
                    document.getElementById('senhaGerada').textContent = cliente.senha_gerada;
                    document.getElementById('tipoSenha').textContent = cliente.tipo_senha.toUpperCase();
                    document.getElementById('senhaAnterior').textContent = cliente.senha_anterior;

                    // Atualiza a narração
                    const narracaoTexto = `Chamada a senha ${cliente.senha_gerada}, por favor, dirija-se ao caixa.`;
                    document.getElementById('narracaoTexto').textContent = narracaoTexto;

                    // Toca o áudio quando novos dados forem recebidos
                    tocarAudio();
                }
            });
        }

        // Chamada para buscar dados a cada 5 segundos
        setInterval(buscarDadosCliente, 5000);

        // Mostrar ou esconder o anúncio em mobile após 10 segundos
        setTimeout(() => {
            document.querySelector('.anuncio-content').style.display = 'block';
        }, 10000);

        document.getElementById('fecharAnuncio').addEventListener('click', function() {
            document.querySelector('.anuncio-content').style.display = 'none';
        });
    </script>
    
    <footer class="footer">
        <p>© 2024 Sis Panel. Todos os direitos reservados. | <a href="https://social.x10.mx">Social Media</a></p>
    </footer>
</body>
</html>
