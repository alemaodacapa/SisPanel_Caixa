function atualizarPainel() {
    setInterval(() => {
        $.ajax({
            url: '/consultar_clientes.php', // Verifique se a URL está correta
            method: 'GET',
            dataType: 'json', // Adicionando dataType para garantir que a resposta seja tratada como JSON
            success: function(dados) {
                if (dados.error) {
                    console.error('Erro na resposta do servidor:', dados.error);
                } else {
                    // Atualize o conteúdo da página com os dados recebidos
                    $('#conteudoPainel').html(`
                        <tr>
                            <td>${dados.id}</td>
                            <td>${dados.nome}</td>
                            <td>${dados.tipo_senha}</td>
                            <td>${dados.senha_gerada}</td>
                        </tr>
                    `);
                }
            },
            error: function(error) {
                console.error('Erro ao atualizar o painel:', error);
            }
        });
    }, 1000); // Consultar a cada 1 segundo
}

// Chame a função para começar o polling quando a página carregar
$(document).ready(() => {
    atualizarPainel();
});
