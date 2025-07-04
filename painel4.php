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
            background-color: #e9eff0; /* Fundo mais claro */
            color: #333;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .barraSuperior {
            background-color: #1877f2; /* Azul do Facebook */
            padding: 10px 20px; /* Menos padding */
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px; /* Bordas arredondadas */
        }

        .uespiLogo {
            height: 40px; /* Menor logo */
        }

        .uespiTexto {
            font-size: 20px; /* Menor tamanho do texto */
            font-weight: bold;
        }

        .subtitulo {
            font-size: 14px; /* Menor subtítulo */
            font-style: italic;
        }

        .container.page {
            padding: 20px;
        }

        .campo-caixa {
            background-color: #4b4f54; /* Cor de fundo das caixas */
            border-radius: 15px;
            padding: 25px; /* Ajuste no padding */
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
            background-color: #ffc107; /* Cor da caixa do usuário */
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
                padding: 30px;
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
            background-color: #28a745; /* Verde para o anúncio */
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin: 20px 0; /* Adiciona margem superior e inferior */
        }

        .anuncio-content button {
            background-color: #dc3545; /* Vermelho para o botão */
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
            background-color: #6f42c1; /* Cor do narrador */
            color: white;
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
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
                    </div>
                    <a href="https://painelsummerside.com.br" class="botao-visitar" style="color: white">Visitar o Site</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para tocar o áudio
        function tocarAudio() {
            const audio = new Audio('path/to/audio/file.mp3'); // Substitua pelo caminho do seu áudio
            audio.play().catch(e => console.log(e));
        }

        // Função para buscar dados do cliente
        function buscarDadosCliente() {
            fetch('https://api.exemplo.com/dados') // Altere para a URL da sua API
                .then(response => response.json())
                .then(cliente => {
                    document.getElementById('nomeCliente').textContent = cliente.nome;
                    document.getElementById('senhaGerada').textContent = cliente.senha_gerada;
                    document.getElementById('tipoSenha').textContent = cliente.tipo_senha.toUpperCase();
                    document.getElementById('senhaAnterior').textContent = cliente.senha_anterior;

                    // Atualiza a narração
                    const narracaoTexto = `Chamada a senha ${cliente.senha_gerada}, por favor, dirija-se ao caixa.`;
                    document.getElementById('narracaoTexto').textContent = narracaoTexto;

                    // Toca o áudio quando novos dados forem recebidos
                    tocarAudio();
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
