$(document).ready(function() {
    let ultimaSenha = '';

    // Função para tocar o som de notificação
    function tocarSom() {
        const audio = new Audio('audio/chamada.wav'); // Caminho para o arquivo de áudio
        audio.play().catch((e) => console.log('Erro ao tocar o som:', e));
    }

    // Função para atualizar a página com os dados recebidos
    function atualizarPagina(data) {
        if (data.senha !== ultimaSenha) {
            // Atualizar os dados na página
            $('#tipoSenha').text(data.tipo_senha.toUpperCase());
            $('#senhaAnterior').text(ultimaSenha);
            $('#nomeCliente').text(data.nome);
            $('#senhaGerada').text(data.senha);

            ultimaSenha = data.senha;

            // Tocar som
            tocarSom();
        }
    }

    // Função para simular a recepção de dados (substitua isto pela chamada real da API ou outra fonte de dados)
    function obterDados() {
        // Exemplo de dados simulados
        const dadosSimulados = {
            tipo_senha: 'normal',
            nome: 'Cliente Teste',
            senha: '1234'
        };
        atualizarPagina(dadosSimulados);
    }

});
