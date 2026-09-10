//cria o banco de dados
CREATE DATABASE meus_agendamentos;
// cria tabela galeria
CREATE TABLE tb_galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imagem VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

