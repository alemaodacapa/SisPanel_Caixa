# Painel de Atendimento

Este painel de atendimento foi criado para gerenciar senhas de clientes em um ambiente com um único caixa de atendimento. Ele permite a emissão de senhas normais e preferenciais, atualização automática da interface, e funcionalidades de narração e som de notificação para uma experiência de usuário eficiente e prática.

## Funcionalidades

### Tipos de Senhas
- **Senhas Normais:** Variam entre **0000** e **9999**.
- **Senhas Preferenciais:** Variam entre **P0000** e **P9999**.

### Painel Administrativo (`registro.php`)
- **Senha de Admin:** A senha para acessar o painel administrativo é **"admincaixa"**.
  - **Importante:** Esta senha serve apenas para evitar que outras pessoas causem transtornos ou tentem comprometer o sistema. No entanto, não garante proteção total contra tais ações.
  - **Aviso:** **Não divulgue o link do painel administrativo** (`registro.php`). Divulgar o link pode permitir que pessoas mal-intencionadas descubram e acessem o sistema.

### Funcionalidades do Painel
- **Som de Notificação:** Um som de notificação é tocado sempre que uma nova senha é gerada.
- **Atualização Automática:** A interface do painel é atualizada automaticamente a cada 25 segundos, garantindo que as informações exibidas estejam sempre atualizadas.
- **Narração:** O painel narra o nome do cliente e a senha gerada no painel administrativo, melhorando a acessibilidade e a experiência do usuário.

### Relatório de Atendimento
- No painel administrativo, é possível visualizar um relatório detalhado de atendimentos, incluindo a distinção entre senhas normais e preferenciais.
- **Nota:** Neste painel específico, não há a funcionalidade de guichê, pois foi desenvolvido para ambientes com apenas um caixa de atendimento. No entanto, a opção de guichês pode ser adicionada se necessário.

## Requisitos para Funcionamento
Para utilizar este painel de atendimento, é necessário:

1. **Hospedagem:**
   - Crie uma conta de hospedagem. Recomendamos a [Hostinger](https://hostinger.com.br?REFERRALCODE=1LEONARDO36) para garantir um serviço seguro e confiável.

2. **Banco de Dados:**
   - É preciso configurar um banco de dados para armazenar as informações dos clientes e senhas geradas.
   - Os dados de conexão, como nome de usuário, nome do banco de dados, senha, e `localhost`, devem ser preenchidos no arquivo `conexao.php` e em outros locais necessários para garantir a correta comunicação com o banco de dados.

## Contato

Se você tiver dúvidas, sugestões ou precisar de ajuda em relação ao painel de atendimento, entre em contato com **Leonardo** através do link: [https://social.x10.mx](https://social.x10.mx).

## Agradecimentos

Agradecemos por adquirir e utilizar este painel de atendimento. Esperamos que ele atenda às suas necessidades e ofereça uma solução eficiente para o seu ambiente de trabalho.
