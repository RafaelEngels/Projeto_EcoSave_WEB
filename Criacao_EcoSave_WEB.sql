--Criacao do Schemae e das tabelas de inserçao de dados.

CREATE DATABASE bd_tde_web;
Use bd_tde_web;

CREATE TABLE usuarios(
	id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome varchar(100) NOT NULL,
    email varchar(100) NOT NULL UNIQUE,
    data_nascimento date NOT NULL,
    telefone varchar(20) UNIQUE,
    senha text NOT NULL,
    chave text NOT NULL
);
CREATE TABLE denuncias(
id_denuncia INT PRIMARY KEY AUTO_INCREMENT,
data_denuncia DATE NOT NULL,
local_denuncia text NOT NULL,
desc_denuncia text NOT NULL,
num_animais INT NOT NULL,
especie varchar(30) NOT NULL
);


