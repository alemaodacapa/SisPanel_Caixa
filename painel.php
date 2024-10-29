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
        flex-direction: column;
        align-items: center;
        flex: 1;
        padding: 20px;
        gap: 20px;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* Estilo das Caixas */
    .caixas-row {
        display: flex;
        gap: 20px;
        justify-content: center;
        width: 100%;
        max-width: 100%;
        flex-wrap: wrap;
    }

    /* Configurações para Desktop */
    @media (min-width: 769px) {
        .caixa {
            flex: 1;
            min-width: 300px;
            max-width: 600px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }
    }

    .caixa:hover {
        transform: scale(1.05);
    }

    /* Caixa de vídeo */
    .caixa-video {
        background-color: transparent;
        position: relative;
        max-width: 80vw;
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
        position: relative;
    }

    .footer a {
        color: #ffff00;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
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
        /* Exibe o anúncio apenas em telas maiores */
    @media (min-width: 769px) {
        .anuncio-lateral {
            width: 120px;
            height: 600px;
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            background-color: #ccc; /* Fundo temporário para visualização */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
    }

    /* Oculta o anúncio em telas menores */
    @media (max-width: 768px) {
        .anuncio-lateral {
            display: none;
        }
    }

    /* Ajustes para mobile */
    @media (max-width: 768px) {
        .caixas-row {
            flex-direction: column;
            align-items: center;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .caixa {
            min-width: 300px;
            max-width: 400px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }

        .caixa:hover {
            transform: scale(1.05);
        }

        .caixa-video {
            width: 100%;
        }

        body {
            overflow-y: auto; /* Permite rolagem no mobile */
        }
    }
</style>
</head>
<body>
    <!-- Anúncio lateral -->
    <div class="anuncio-lateral">
        <!-- Imagem ou código do anúncio aqui -->
        <a href="https://pagbank.vc/indica-maquininhas-e938379fe"><img src="/img/seguranca_120x600.gif" alt="Banner PagSeguro" title="Compre com PagSeguro e fique sossegado" style="width: 100%; height: 600px;"></a>
    </div>
    <!-- Conteúdo principal da página -->

    <div class="barraSuperior">ATENDIMENTO - CHAMADA POR SENHA</div>

    <div class="container">
        <div class="caixas-row">
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
            <div class="caixa">
                <div class="caixa-titulo">HORÁRIO</div>
                <h1>
                    <div id="relogio">
                        <strong style="font-size: 33px; font-weight: bold;"></strong>
                    </div>
                </h1>
            </div>
        </div>

        <div class="caixa-video">
            <video autoplay muted loop>
                <source src="video/SisPanel.mp4" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
            <a href="https://pay.hotmart.com/Y95202654S?checkoutMode=2" class="video-overlay-link"></a>
        </div>
    </div>

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

    <audio id="audioChamada" src="audio/chamada.wav"></audio>

    <footer class="footer">
        <p>© 2024 Sis Panel. Todos os direitos reservados. <a href="https://social.x10.mx">Social Media</a></p>
    </footer>
    
</body>
</html>




