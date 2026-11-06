# Biblioteca Digital Integrada - API REST PHP POO

Sistema backend para gestão de empréstimos, materiais, acervo digital e utilizadores desenvolvido em PHP 8.2+ orientado a objetos.

## Requisitos
- PHP 8.2 ou superior
- MySQL 8.0+ / MariaDB
- Composer
- Extensão PDO PHP habilitada

## Instalação e Configuração
1. Clone o repositório.
2. Instale as dependências:
   ```bash
   composer install
   ```
3. Importe os scripts SQL na sua base de dados MySQL:
   ```bash
   mysql -u root -p < schema.sql
   mysql -u root -p < seed.sql
   ```

## Execução dos Testes Automatizados (PHPUnit)
Para executar a suite de testes unitários:
```bash
./vendor/bin/phpunit tests
```

## Endpoints da API REST

| Método | Endpoint | Descrição | Perfis Permitidos |
|---|---|---|---|
| POST | `/api/auth/login` | Autenticação e geração de credenciais | Todos |
| GET | `/api/materiais` | Lista materiais com filtros e paginação | Todos |
| GET | `/api/materiais/{id}` | Consulta detalhada do material | Todos |
| POST | `/api/materiais` | Regista novo material | Administrador, Bibliotecário |
| PUT | `/api/materiais/{id}` | Atualiza material | Administrador |
| GET | `/api/usuarios` | Lista utilizadores cadastrados | Administrador |
| POST | `/api/usuarios` | Regista novo utilizador | Administrador |
| GET | `/api/emprestimos` | Lista empréstimos | Administrador, Bibliotecário |
| POST | `/api/emprestimos` | Regista novo empréstimo | Administrador, Bibliotecário |
| GET | `/api/emprestimos/{id}` | Consulta empréstimo por ID | Administrador, Bibliotecário, Leitor (próprio) |
| POST | `/api/emprestimos/{id}/devolucao` | Regista devolução e calcula multa | Administrador, Bibliotecário |
| GET | `/api/multas` | Consulta multas pendentes | Administrador |
| GET | `/api/historico` | Registo de movimentações | Administrador |
| GET | `/api/relatorios/resumo` | Relatório gerencial consolidado | Administrador |

## Princípios SOLID Aplicados
- **SRP (Single Responsibility Principle):** Separação estrita entre modelos, repositórios, serviços e controllers.
- **OCP (Open/Closed Principle):** A classe `Pesquisador` estende `Usuario` adicionando regras próprias sem modificar as classes existentes.
- **LSP (Liskov Substitution Principle):** Qualquer subclasse de `Usuario` ou `Material` pode ser utilizada onde a classe abstrata base é esperada.
- **ISP (Interface Segregation Principle):** Interfaces de repositórios concisas e especializadas.
- **DIP (Dependency Inversion Principle):** `EmprestimoService` e `DevolucaoService` dependem de interfaces de repositório e não de implementações PDO diretas.