//cria o banco de dados
CREATE DATABASE meus_agendamentos;
// cria tabela galeria
CREATE TABLE tb_galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imagem VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

//cria a tabela para realizar os meus_agendamentos

CREATE TABLE tb_agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nome_cliente VARCHAR(150) NOT NULL,

    telefone VARCHAR(20) NOT NULL,

    data_agendamento DATE NOT NULL,

    hora_agendamento TIME NOT NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unico_agendamento (
        data_agendamento,
        hora_agendamento
    )
);