# Gestão Financeira — Scaffold

Projeto exemplo de gestão financeira com frontend (HTML/CSS/JS) e backend em PHP.

Estrutura criada:
- index.php (entrada web)
- login.php
- register.php
- logout.php
- api/transactions.php
- assets/css/style.css
- assets/js/app.js
- src/config.php
- src/db.php
- src/api/transactions.php
- sql/schema.sql
- data/ (banco SQLite)
- suporte a categorias de transação e filtros no dashboard

Como rodar (modo dev com PHP embutido):

```bash
cd gestao-financeira
php -S localhost:8000
```

Inicializar banco SQLite (duas opções):

Opção 1 — usando `sqlite3`:

```bash
# na raiz do projeto
sqlite3 data/gestao.db < sql/schema.sql
```

Opção 2 — script PHP que cria as tabelas e um usuário admin:

```bash
php src/init_db.php
```

> Se você receber um erro sobre o driver `PDO_SQLITE`, habilite o `pdo_sqlite` no seu PHP ou instale o pacote correspondente.
>
> No Windows/WSL, verifique `php.ini` e ative `extension=pdo_sqlite`.

Se preferir MySQL, ajuste `src/config.php` com a DSN e credenciais.

A API básica está em `src/api/transactions.php`.
