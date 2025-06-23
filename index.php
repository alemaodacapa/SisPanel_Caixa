<?php
session_start();

// Define a senha padrão
$senha_padrao = "admincaixa";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha'])) {
    if ($_POST['senha'] === $senha_padrao) {
        $_SESSION['acesso_permitido'] = true;
    } else {
        $erro = "Senha incorreta!";
        $_SESSION['acesso_permitido'] = false;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta property="og:image" content="/img/Banner.jpg">
    <title>SisPainel - Acesso</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background: linear-gradient(to right, #0056b3, #007bff);
            font-family: Arial, sans-serif;
            color: #fff;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .center-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-box {
            background: #ffffff;
            color: #000;
            border-radius: 15px;
            padding: 30px 25px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .login-box h3 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #0056b3;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-login {
            background-color: #0056b3;
            color: white;
            border-radius: 10px;
            font-weight: bold;
            width: 100%;
        }

        .btn-login:hover {
            background-color: #003f88;
        }

        .info-box {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            color: #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .info-box h3 {
            margin-top: 0;
        }

        .btn-large {
            font-size: 1.2em;
            padding: 10px;
            width: 100%;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>

<body>

<?php if (!isset($_SESSION['acesso_permitido']) || $_SESSION['acesso_permitido'] !== true): ?>
    <div class="center-wrapper">
        <form method="POST" action="">
            <div class="login-box">
                <img src="img/att.jpg" alt="Logo" class="logo img-fluid mb-3 d-block mx-auto" style="height: 60px;"/>
                <h3>SisPainel - Acesso</h3>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger text-center"><?php echo $erro; ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="senha"><i class="fas fa-lock"></i> Digite a senha:</label>
                    <input type="password" id="senha" name="senha" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-login mt-3">Entrar</button>
            </div>
        </form>
    </div>
<?php else: ?>

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>SisPainel Caixa - Operador</h1>
        </div>

        <div class="info-box">
            <h3>Cadastro de Usuário</h3>
            <form id="formCadastro">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tipo de Senha:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_senha" id="normal" value="normal" checked>
                        <label class="form-check-label" for="normal">Normal</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_senha" id="preferencial" value="preferencial">
                        <label class="form-check-label" for="preferencial">Preferencial</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="text" id="senha" name="senha" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-large" onclick="gerarSenhaAleatoria()">Gerar Senha</button>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-2">
                        <button type="button" class="btn btn-success btn-large" onclick="cadastrarCliente()">Cadastrar</button>
                    </div>
                    <div class="col-md-6 mb-2">
                        <a href="/relatorio.php" target="_blank" class="btn btn-info btn-large">Consultar Relatório</a>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-warning btn-large" onclick="atualizarPainel()">Atualizar Painel</button>
                </div>
            </form>
            <div id="mensagem" class="alert mt-3" style="display: none;"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function gerarSenhaAleatoria() {
            let tipo_senha = $('input[name="tipo_senha"]:checked').val();
            let senha = tipo_senha === 'preferencial' ? 'P' + Math.floor(1000 + Math.random() * 9000) : Math.floor(1000 + Math.random() * 9000);
            $('#senha').val(senha);
        }

        function cadastrarCliente() {
            let nome = $('#nome').val();
            let tipo_senha = $('input[name="tipo_senha"]:checked').val();
            let senha = $('#senha').val();

            $.post('processa_cadastro_usuarios.php', {
                nome: nome,
                tipo_senha: tipo_senha,
                senha: senha
            })
            .done(() => {
                $('#mensagem').removeClass('alert-danger').addClass('alert-success').text('Cadastro realizado com sucesso!').show();
            })
            .fail((xhr) => {
                $('#mensagem').removeClass('alert-success').addClass('alert-danger').text('Erro ao cadastrar: ' + xhr.responseText).show();
            });
        }

        function atualizarPainel() {
            window.open('/painel.php', '_blank');
        }
    </script>

<?php endif; ?>
</body>
</html>
