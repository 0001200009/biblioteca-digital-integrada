-- Script de Criação do Banco de Dados: Biblioteca Digital Integrada
CREATE DATABASE IF NOT EXISTS biblioteca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca_db;

DROP TABLE IF EXISTS multas;
DROP TABLE IF EXISTS historico_movimentacoes;
DROP TABLE IF EXISTS emprestimos;
DROP TABLE IF EXISTS ebooks;
DROP TABLE IF EXISTS revistas;
DROP TABLE IF EXISTS livros;
DROP TABLE IF EXISTS materiais;
DROP TABLE IF EXISTS contas_acesso;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    tipo ENUM('Aluno', 'Professor', 'Pesquisador') NOT NULL,
    limite_emprestimos INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE contas_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    perfil ENUM('Administrador', 'Bibliotecario', 'Leitor') NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE materiais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    tipo ENUM('Livro', 'Revista', 'EBook') NOT NULL,
    quantidade_disponivel INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE livros (
    material_id INT PRIMARY KEY,
    autor VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    paginas INT NOT NULL,
    FOREIGN KEY (material_id) REFERENCES materiais(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE revistas (
    material_id INT PRIMARY KEY,
    issn VARCHAR(20) NOT NULL UNIQUE,
    edicao INT NOT NULL,
    editora VARCHAR(100) NOT NULL,
    FOREIGN KEY (material_id) REFERENCES materiais(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE ebooks (
    material_id INT PRIMARY KEY,
    formato VARCHAR(20) NOT NULL,
    tamanho_mb DECIMAL(8,2) NOT NULL,
    url_download VARCHAR(255) NOT NULL,
    FOREIGN KEY (material_id) REFERENCES materiais(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE emprestimos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    material_id INT NOT NULL,
    data_emprestimo DATETIME NOT NULL,
    data_prevista_devolucao DATETIME NOT NULL,
    data_devolucao DATETIME NULL,
    status ENUM('ATIVO', 'DEVOLVIDO', 'ATRASADO') NOT NULL DEFAULT 'ATIVO',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (material_id) REFERENCES materiais(id)
) ENGINE=InnoDB;

CREATE TABLE multas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emprestimo_id INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    dias_atraso INT NOT NULL,
    paga TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emprestimo_id) REFERENCES emprestimos(id)
) ENGINE=InnoDB;

CREATE TABLE historico_movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    material_id INT NOT NULL,
    tipo_operacao ENUM('EMPRESTIMO', 'DEVOLUCAO') NOT NULL,
    data_operacao DATETIME NOT NULL,
    detalhes TEXT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (material_id) REFERENCES materiais(id)
) ENGINE=InnoDB;