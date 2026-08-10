CREATE DATABASE  cerrado_burguer;

USE cerrado_burguer;

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(255),
    telefone VARCHAR(20),
    endereco VARCHAR(500),
    cpf VARCHAR(14) UNIQUE

);
