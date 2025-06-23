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
    <title>Sis Panel - Gerenciador de Filas Online</title>

    <!-- Favicon -->
    <link rel="icon" href="/img/att.jpg" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="public/images/apple-touch-icon.png" />

    <!-- Meta Tags -->
    <meta name="keywords" content="Gerenciador de Filas, Painel de Atendimento, Gestão de Senhas, Senhas Preferenciais, Senhas Normais, Caixa de Atendimento, Atualização Automática, Narração de Senhas, Som de Notificação, Painel Administrativo, Relatório de Atendimento, Senhas Automáticas, Sistema de Fila Única, Interface de Atendimento, Sistema de Senhas Online, Gestão de Clientes, Atendimento ao Cliente, Acessibilidade no Atendimento, Senhas Eletrônicas, Solução de Filas">
    <meta property="og:description" content="Sis Panel - Gerenciador de Filas Online">
    <meta property="og:image" content="https://caixa.e-painel.x10.mx/img/banner.jpg">
    <meta name="robots" content="index,follow">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        body {
            background-color: #f0f0f0;
            color: #333;
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
            display: flex;
            align-items: center;
        }

        .uespiLogo {
            height: 80px;
            margin-right: 20px;
        }

        .uespiTexto {
            font-size: 24px;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 18px;
        }

        .container.page {
            padding: 20px;
        }

        .campo-caixa {
            background-color: #007bff;
            border-radius: 5px;
            padding: 20px;
            font-size: 30px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .campo-caixa:hover {
            background-color: #0056b3; /* Muda a cor ao passar o mouse */
        }

        .campo-caixa-usuario {
            background-color: #ffff00;
            color: #000000;
            font-size: 30px;
            padding: 15px;
            font-weight: bold;
            border-radius: 5px;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        /* Design responsivo */
        @media (max-width: 768px) {
            .campo-caixa {
                font-size: 24px;
                padding: 15px;
            }

            .campo-caixa-usuario {
                font-size: 20px;
            }
        }

        @media (min-width: 769px) {
            .campo-caixa {
                font-size: 35px;
                padding: 25px;
            }

            .campo-caixa-usuario {
                font-size: 28px;
            }
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

        /* Anúncios */
        .anuncio-content {
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
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
    </style>
</head>
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
    <div class="footer">
        <p><a href="https://social.x10.mx">Sis Panel</a> Todos os direitos reservados</p>
    </div>

    <script>
        // Narrador compatível com móbile
        let iniciado = false;

        function narrarTexto(texto) {
            if ('speechSynthesis' in window) {
                const voices = window.speechSynthesis.getVoices();
                const voz = voices.find(v => v.lang === 'pt-BR') || voices.find(v => v.lang.startsWith('pt'));
                const frase = new SpeechSynthesisUtterance(texto);
                frase.lang = 'pt-BR';
                if (voz) frase.voice = voz;
                speechSynthesis.speak(frase);
            }
        }

        function iniciarNarrador() {
            if (!iniciado) {
                iniciado = true;
                const nome = document.getElementById('nomeCliente').textContent;
                const senha = document.getElementById('senhaGerada').textContent;
                narrarTexto(`Senha gerada para ${nome}, ${senha}`);
            }
        }

        document.addEventListener('click', iniciarNarrador);
        document.addEventListener('touchstart', iniciarNarrador);
    </script>
</body>
</html>
