-- Script de Inserção de Dados Iniciais
USE biblioteca_db;

-- Inserir Usuários Obrigatórios
INSERT INTO usuarios (id, nome, email, tipo, limite_emprestimos) VALUES
(1, 'Ana Souza', 'ana.souza@email.com', 'Aluno', 3),
(2, 'Carlos Mendes', 'carlos.mendes@email.com', 'Professor', 5),
(3, 'Marina Costa', 'marina.costa@email.com', 'Pesquisador', 7);

-- Inserir Contas de Acesso (Senhas hashed com password_hash('123456', PASSWORD_BCRYPT))
-- $2y$10$e0MYzXyjpJS7Pd0RVvHwHe1z4J5N8xV7N2tKz/l4K9L1xL5p4G8i6 = '123456'
INSERT INTO contas_acesso (usuario_id, senha_hash, perfil) VALUES
(1, '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1z4J5N8xV7N2tKz/l4K9L1xL5p4G8i6', 'Leitor'),
(2, '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1z4J5N8xV7N2tKz/l4K9L1xL5p4G8i6', 'Bibliotecario'),
(3, '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1z4J5N8xV7N2tKz/l4K9L1xL5p4G8i6', 'Administrador');

-- Inserir Materiais Obrigatórios
INSERT INTO materiais (id, titulo, tipo, quantidade_disponivel) VALUES
(1, 'PHP Orientado a Objetos', 'Livro', 3),
(2, 'Tecnologia Hoje', 'Revista', 2),
(3, 'APIs REST com PHP', 'EBook', 5);

INSERT INTO livros (material_id, autor, isbn, paginas) VALUES
(1, 'Lúcio Silva', '978-85-7522-000-1', 320);

INSERT INTO revistas (material_id, issn, edicao, editora) VALUES
(2, '1234-5678', 42, 'TechMedia');

INSERT INTO ebooks (material_id, formato, tamanho_mb, url_download) VALUES
(3, 'PDF', 12.50, 'https://biblioteca.digital/ebooks/apis-rest-php.pdf');