const express = require('express');
const mysql = require('mysql2');

// Configuração do servidor
const app = express();
const port = 3000;

// Configuração do Banco de Dados MySQL
const db = mysql.createConnection({
    host: 'localhost',
    user: 'seu_usuario',
    password: 'sua_senha',
    database: 'seu_database'
});

db.connect((err) => {
    if (err) throw err;
    console.log('Conectado ao banco de dados!');
});

// Rota de cadastro de clientes
app.use(express.json());

app.post('/cadastro', (req, res) => {
    const { nome, tipo_senha, senha } = req.body;
    const query = 'INSERT INTO clientes (nome, tipo_senha, senha) VALUES (?, ?, ?)';

    db.query(query, [nome, tipo_senha, senha], (err, result) => {
        if (err) {
            console.error("Erro ao cadastrar:", err);
            res.status(500).json({ message: "Erro ao cadastrar o cliente." });
            return;
        }
        console.log('Novo cliente cadastrado!');

        // Retorna a resposta de sucesso ao cliente
        res.status(201).json({ message: 'Cliente cadastrado com sucesso!' });
    });
});

// Rota para consultar o último cliente cadastrado (para o polling)
app.get('/consultar_clientes', (req, res) => {
    const query = 'SELECT * FROM clientes ORDER BY id DESC LIMIT 1'; // Alterado para pegar apenas o último registro
    db.query(query, (err, results) => {
        if (err) {
            console.error("Erro ao consultar clientes:", err);
            return res.status(500).json({ message: "Erro ao consultar clientes." });
        }
        if (results.length === 0) {
            return res.status(404).json({ message: "Nenhum cliente cadastrado." });
        }
        res.json(results[0]); // Retorna apenas o último registro
    });
});

// Middleware para tratamento de erros
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({ message: "Algo deu errado!" });
});

// Inicialização do servidor
const server = app.listen(port, () => {
    console.log(`Servidor rodando em http://localhost:${port}`);
});
