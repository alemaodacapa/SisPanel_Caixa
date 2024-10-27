<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <!-- Favicon -->
    <link href="/img/att.jpg" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Gerenciador de Filas, Painel de Atendimento, Gestão de Senhas, Senhas Preferenciais, Senhas Normais, Caixa de Atendimento, Atualização Automática, Narração de Senhas, Som de Notificação, Painel Administrativo, Relatório de Atendimento, Senhas Automáticas, Sistema de Fila Única, Interface de Atendimento, Sistema de Senhas Online, Gestão de Clientes, Atendimento ao Cliente, Acessibilidade no Atendimento, Senhas Eletrônicas, Solução de Filas"/>
    <meta property="og:description" content="Sis Panel - Gerenciador de Filas Online"/>
    <meta property="og:image" content="https://caixa.e-painel.x10.mx/imagens/SisPanel.jpg" />
    <title>Sis Panel - Gerenciador de Filas Online</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="lib/jquery-3.3.1.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/inject.js"></script>
    <script src="js/funcoes_painel.js"></script>
    <script src="js/painel.js"</script>
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
    <div class="barraSuperior">
        <div class="row">
            <div class="col-xs-1">
                <img src="img/att.jpg" class="uespiLogo" alt="Logo" style="width: 80px; height: auto;">
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
                <div class="caixa-normal" style="font-size: 22px;">
                    <div><strong>CAIXA</strong></div>
                    <div><strong id="tipoSenha"><?php echo strtoupper($cliente['tipo_senha']); ?></strong></div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="caixa-anterior" style="font-size: 22px;">
                    <div><strong>ANTERIORES</strong></div>
                    <div id="senhaAnterior"><?php echo $senha_anterior; ?></div>
                </div>
            </div>
        </div>

<div class="row">
    <div class="col-xs-6">
        <!-- Caixa do Usuário -->
        <div class="campo-caixa" style="font-size: 22px;">
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

    <!-- Anúncio para Desktop -->
    <div class="col-xs-6 d-none d-md-block">
        <div id="anuncioDesktop" class="anuncio-desktop">
            <div class="anuncio-content-desktop campo-caixa" style="font-size: 22px;">
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
    <!-- Anúncio -->
    <div class="col-xs-6 d-md-none">
        <div id="anuncioMobile" class="anuncio-mobile" style="display: none;">
            <div class="anuncio-content-mobile">
                <button id="fecharAnuncio">X</button>
                <div class="campo-caixa" style="font-size: 22px;">
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
    <!-- Rodapé -->
    <footer class="footer text-center">
        <p>
            <a href="https://social.x10.mx" target="_blank" rel="noopener noreferrer">©Sis Panel</a> - Todos os direitos reservados
        </p>
    </footer>
</body>
</html>
