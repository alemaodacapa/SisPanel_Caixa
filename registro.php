<?php 
session_start();

$senha_padrao = "admincaixa";

// Redefinir a sessão se o usuário não fornecer a senha correta
if (isset($_POST['senha']) && $_POST['senha'] === $senha_padrao) {
    $_SESSION['acesso_permitido'] = true;
} else {
    $_SESSION['acesso_permitido'] = false;
}

// Se o acesso não for permitido, exibir o formulário de senha
if (!isset($_SESSION['acesso_permitido']) || $_SESSION['acesso_permitido'] !== true) {
    echo '<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SisPainel Caixa - Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container" style="margin-top: 50px;">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3 class="text-center">Acesso Restrito</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="senha">Senha: admincaixa</label>
                        <input type="password" id="senha" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Acessar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>';
    exit();
}

include 'conexao.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Desativa o menu de contexto do botão direito
            document.addEventListener('contextmenu', function(event) {
                event.preventDefault();
                alert("O botão direito está desativado nesta página.");
            });
        });
    </script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SisPainel Caixa - Operador</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-large {
            font-size: 1.5em;
            padding: 10px;
            width: 100%;
        }

        .info-box {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .info-box h3 {
            margin-top: 0;
        }

        .radio-group {
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>SisPainel Caixa - Operador</h1>
        </div>

        <div class="info-box">
            <h3>Cadastro de Usuário</h3>
            <form id="formCadastro">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="form-group radio-group">
                    <label>Tipo de Senha:</label><br>
                    <input type="radio" id="normal" name="tipo_senha" value="normal" checked>
                    <label for="normal">Normal</label><br>
                    <input type="radio" id="preferencial" name="tipo_senha" value="preferencial">
                    <label for="preferencial">Preferencial</label>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="text" id="senha" name="senha" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-large" onclick="gerarSenhaAleatoria()">Gerar Senha</button>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <button type="button" class="btn btn-success btn-large" onclick="cadastrarCliente()">Cadastrar</button>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="/relatorio.php" target="_blank" class="btn btn-info btn-large">Consultar Relatório</a>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-warning btn-large" onclick="atualizarPainel()">Atualizar Painel</button>
                </div>
            </form>
            <div id="mensagem" class="alert" style="display: none;"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function gerarSenhaAleatoria() {
            var tipo_senha = $('input[name="tipo_senha"]:checked').val();
            var senha;

            if (tipo_senha === 'normal') {
                senha = Math.floor(1000 + Math.random() * 9000);  // Senha de 4 dígitos
            } else if (tipo_senha === 'preferencial') {
                senha = 'P' + Math.floor(1000 + Math.random() * 9000);  // Senha de 4 dígitos com prefixo 'P'
            }

            $('#senha').val(senha);
        }

        function cadastrarCliente() {
            var nome = $('#nome').val();
            var tipo_senha = $('input[name="tipo_senha"]:checked').val();
            var senha = $('#senha').val();

            $.ajax({
                type: 'POST',
                url: 'processa_cadastro_usuarios.php',
                data: { nome: nome, tipo_senha: tipo_senha, senha: senha },
                success: function(response) {
                    $('#mensagem').removeClass('alert-danger').addClass('alert-success').text('Cadastro realizado com sucesso!').show();
                },
                error: function(xhr, status, error) {
                    $('#mensagem').removeClass('alert-success').addClass('alert-danger').text('Erro ao cadastrar o cliente: ' + error).show();
                }
            });
        }

        function atualizarPainel() {
            window.open('/painel.php', '_blank');
        }
    </script>
</body>

</html>
