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
  <link rel="icon" href="/img/att.jpg">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="lib/jquery-3.3.1.min.js"></script>
  <script src="js/main.js"></script>
  <script src="js/inject.js"></script>
  <script src="js/funcoes_painel.js"></script>
  <script src="js/painel.js"></script>
  <style>
    body {
      background-color: #ffffff;
      color: #333;
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .barraSuperior {
      background-color: #0056b3;
      padding: 15px;
      color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .barraSuperior img {
      height: 60px;
    }

    .titulo-texto {
      flex: 1;
      text-align: center;
    }

    .titulo-texto span {
      display: block;
      font-size: 20px;
      font-weight: bold;
    }

    .titulo-texto .subtitulo {
      font-size: 14px;
      font-weight: normal;
    }

    .info-link {
      color: #ffff00;
      text-decoration: none;
    }

    .container.page {
      flex: 1;
      padding: 20px;
    }

    .campo-caixa, .caixa-anterior {
      background-color: #007bff;
      border-radius: 8px;
      padding: 20px;
      font-size: 28px;
      text-align: center;
      color: white;
      margin-bottom: 20px;
    }

    .campo-caixa-usuario {
      background-color: #ffff00;
      color: #000;
      font-size: 28px;
      padding: 20px;
      font-weight: bold;
      border-radius: 8px;
      margin-top: 10px;
    }

    .footer {
      background-color: #0056b3;
      color: white;
      text-align: center;
      padding: 10px;
      font-size: 13px;
    }

    .footer a {
      color: #ffff00;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 767px) {
      .campo-caixa, .caixa-anterior, .campo-caixa-usuario {
        font-size: 22px;
        padding: 15px;
      }

      .titulo-texto span {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>
  <div class="barraSuperior">
    <img src="img/att.jpg" alt="Logo">
    <div class="titulo-texto">
      <span>ATENDIMENTO</span>
      <span class="subtitulo">Chamada por Senha</span>
    </div>
    <a href="https://social.x10.mx" class="info-link">
      <i class="fa fa-info-circle"></i>
    </a>
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
        <div class="caixa-anterior">
          <strong>ANTERIORES</strong>
          <div id="senhaAnterior"><?php echo $senha_anterior; ?></div>
        </div>
      </div>
    </div>

    <div class="row">
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

    <script>
        function tocarAudio() {
            const audio = document.getElementById('audioChamada');
            audio.play();
        }

        function narrarTexto(texto) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(texto);
                speechSynthesis.speak(utterance);
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
            setInterval(function() {
                location.reload();
            }, 25000);
        };
    </script>

  <footer class="footer">
    <p>&copy; 2024 Sis Panel. Todos os direitos reservados. | <a href="https://social.x10.mx">Social Media</a></p>
  </footer>
</body>
</html>
