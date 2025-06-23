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
    <title>Painel de Caixa</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="lib/jquery-3.3.1.min.js"></script>
    <script src="js/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta property="og:image" content="/img/Sis.jpg">
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
            padding: 10px 15px;
            color: white;
            height: 80px;
            display: flex;
            align-items: center;
        }

        .uespiLogo {
            height: 50px;
            margin-right: 10px;
        }

        .uespiTexto {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.2;
        }

        .subtitulo {
            font-size: 14px;
            color: #e0e0e0;
            line-height: 1.2;
        }

        .info-link {
            font-size: 13px;
            color: #ffff00;
            text-decoration: none;
        }

        .container.page {
            padding: 10px;
        }

        .campo-caixa {
            background-color: #007bff;
            border-radius: 5px;
            padding: 15px;
            font-size: 35px;
            text-align: center;
            color: white;
            margin-bottom: 15px;
            overflow: hidden;
            width: 95%;
            margin-left: auto;
            margin-right: auto;
        }

        .campo-caixa-usuario {
            background-color: #ffff00;
            color: #000000;
            font-size: 30px;
            padding: 15px;
            font-weight: bold;
        }

        .caixa-normal,
        .caixa-anterior {
            background-color: #0056b3;
            border-radius: 5px;
            padding: 15px;
            font-size: 32px;
            text-align: center;
            color: white;
            margin-bottom: 15px;
            width: 95%;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 767px) {
            .campo-caixa,
            .caixa-normal,
            .caixa-anterior {
                font-size: 30px;
                padding: 12px;
            }

            .campo-caixa-usuario {
                font-size: 24px;
            }

            .barraSuperior {
                height: 70px;
                padding: 8px 12px;
            }

            .uespiLogo {
                height: 40px;
            }

            .uespiTexto {
                font-size: 18px;
            }
        }

        .footer {
            background-color: #0056b3;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            font-size: 14px;
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
        function tocarAudio() {
            const audio = document.getElementById('audioChamada');
            audio.play();
        }

        function narrarTexto(texto) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(texto);
                speechSynthesis.speak(utterance);
            } else {
                alert('Navegador não suporta síntese de fala.');
            }
        }

        function narrarInformacoes() {
            const nomeCliente = document.getElementById('nomeCliente').textContent;
            const senhaGerada = document.getElementById('senhaGerada').textContent;
            narrarTexto(`Senha gerada para ${nomeCliente} é ${senhaGerada}`);
        }

        window.onload = function() {
            tocarAudio();
            narrarInformacoes();
            setInterval(function () {
                location.reload();
            }, 25000);
        };
    </script>
</head>
<body>

    <div class="barraSuperior">
        <img src="img/att.jpg" class="uespiLogo" alt="Logo">
        <div>
            <div class="uespiTexto">ATENDIMENTO</div>
            <div class="subtitulo">Chamada por Senha <a href="https://social.x10.mx" class="info-link"><i class="fa fa-info-circle"></i> Info</a></div>
        </div>
    </div>

    <div class="container page">
        <div class="caixa-normal">
            <div><strong>CAIXA</strong></div>
            <div><strong id="tipoSenha"><?php echo strtoupper($cliente['tipo_senha']); ?></strong></div>
        </div>
        <div class="caixa-anterior">
            <div><strong>ANTERIORES</strong></div>
            <div id="senhaAnterior"><?php echo $senha_anterior; ?></div>
        </div>
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

    <audio id="audioChamada" src="audio/chamada.wav"></audio>

    <div class="footer">
        <p><a href="https://social.x10.mx">Sis Panel</a> Todos os direitos reservados</p>
    </div>
<div style="display: flex; justify-content: center; align-items: center; margin: 10px;">
  <!-- Vídeo Clicável -->
  <a href="https://reggaeroots.com.br/apk/android/app-release.apk"  
     target="_blank" 
     rel="noopener noreferrer" 
     class="video-merchandising-link"
     style="text-decoration: none; display: inline-block; width: 265px; height: auto; margin: 0 10px;">
     
    <div class="video-wrapper">
      <video class="video-merch" muted loop autoplay playsinline>
        <source src="/video/Vídeo_Merchandising.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
      </video>
    </div>
  </a>

  <!-- Banner da Hostinger -->
  <a href="https://cart.hostinger.com/pay/d5f7978e-b403-4e51-9de8-17b6cd763983?_ga=GA1.3.942352702.1711283207" 
     target="_blank"
     style="margin: 0 10px;">
     
    <img src="/img/hostinger.jpeg" 
         alt="Banner Hostinger" 
         title="Seu site no lugar certo" 
         width="73" 
         height="48" 
         class="redesign-image-modernized" 
         style="border: none;">
  </a>
</div>
<br>
<br>
<style>
  .video-wrapper {
    position: relative;
    width: 265px;
    height: 48px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  }

  .video-merch {
    width: 100%;
    height: 48px;
    display: block;
    border: none;
  }
</style>
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
<script>
    let ativado = false;

    function narrarTexto(texto) {
        if ('speechSynthesis' in window) {
            const synth = window.speechSynthesis;
            const voices = synth.getVoices();

            // Tenta encontrar voz em português do Brasil
            let voz = voices.find(v => v.lang === 'pt-BR') || voices.find(v => v.lang.startsWith('pt'));

            const utterance = new SpeechSynthesisUtterance(texto);
            utterance.lang = 'pt-BR';
            utterance.rate = 0.95;
            utterance.pitch = 1;
            if (voz) utterance.voice = voz;

            synth.speak(utterance);
        } else {
            console.warn("API de narração não suportada.");
        }
    }

    function tocarAudio() {
        const audio = document.getElementById('audioChamada');
        if (audio) audio.play().catch(e => console.log('Áudio bloqueado:', e));
    }

    function narrarInformacoes() {
        const nomeCliente = document.getElementById('nomeCliente')?.textContent?.trim();
        const senhaGerada = document.getElementById('senhaGerada')?.textContent?.trim();
        if (nomeCliente && senhaGerada) {
            narrarTexto(`Senha gerada para ${nomeCliente}. Número ${senhaGerada}`);
        }
    }

    function iniciarPainel() {
        if (!ativado) {
            ativado = true;
            // Garante que as vozes estão carregadas
            window.speechSynthesis.getVoices(); 
            setTimeout(() => {
                tocarAudio();
                narrarInformacoes();
            }, 300); // pequena espera para carregar vozes
            setInterval(() => location.reload(), 25000);
        }
    }

    // Espera interação do usuário para ativar
    window.addEventListener('click', iniciarPainel);
    window.addEventListener('touchstart', iniciarPainel);
</script>

</body>
</html>




