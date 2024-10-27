<!DOCTYPE html>
<html class="overflow-x-hidden" lang="pt-br">
<head>
    <!-- Primary Meta Tags -->
    <meta charset="UTF-8">
    <meta name="title" content="Sis Panel - Gerenciador de Filas Online">
    <meta name="description" content="Gerencie suas filas de atendimento de forma eficiente e moderna.">
    <meta name="robots" content="index,follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Sua Empresa">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sis Panel - Gerenciador de Filas Online</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/images/favicon-48x48.png" sizes="48x48" />
    <link rel="apple-touch-icon" sizes="180x180" href="public/images/apple-touch-icon.png" />

    <!-- Google Fonts Preloader -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f9fc;
            color: #333;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .barraSuperior {
            background-color: #1d1f21;
            padding: 20px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .uespiLogo {
            height: 60px;
        }

        .uespiTexto {
            font-size: 24px;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 16px;
            font-style: italic;
        }

        .container.page {
            padding: 20px;
        }

        .campo-caixa {
            background-color: #007bff;
            border-radius: 15px;
            padding: 30px;
            font-size: 28px;
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
            font-size: 28px;
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
                font-size: 24px;
                padding: 20px;
            }

            .campo-caixa-usuario {
                font-size: 20px;
            }
        }

        @media (min-width: 769px) {
            .campo-caixa {
                font-size: 32px;
                padding: 35px;
            }

            .campo-caixa-usuario {
                font-size: 26px;
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