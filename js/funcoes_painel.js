$(document).ready(function() {
    // Função para atualizar os dados do cliente
    function atualizarDadosCliente(cliente) {
        const nomeCliente = cliente.nome || 'Cliente';
        const senha = cliente.senha_gerada || 'Senha';

        // Atualiza a interface com as novas informações
        $('.campo-caixa:nth-of-type(1) div:last-child').text(cliente.tipo_senha);
        $('.campo-caixa:nth-of-type(2) div:last-child').text(cliente.senha_anterior || 'Nenhuma senha anterior');
        $('.campo-caixa:nth-of-type(3) div:last-child').html(`<strong style="font-size: 36px;">${senha}</strong> - ${nomeCliente}`);
    }

    // Função para simular a recepção de dados (substitua isto pela chamada real da API ou outra fonte de dados)
    function obterDados() {
        // Exemplo de dados simulados
        const dadosSimulados = {
            tipo_senha: 'normal',
            nome: 'Cliente Teste',
            senha: '1234',
            senha_anterior: '0000'
        };
        atualizarDadosCliente(dadosSimulados);
    }
