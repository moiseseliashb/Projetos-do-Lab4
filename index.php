<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/auth.php';
require_login();
$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?><!doctype html>
<html lang="pt-br" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Gestão Financeira</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <meta name="color-scheme" content="light dark">
</head>
<body>
  <header class="topbar">
    <div class="brand">
      <span class="brand-icon">💼</span>
      <div>
        <strong>Gestão Financeira</strong>
        <span class="brand-subtitle">Controle simples e elegante</span>
      </div>
    </div>
    <div class="controls">
      <button id="theme-toggle" aria-label="Alternar tema">🌓</button>
      <span class="user">Olá, <?php echo htmlspecialchars(current_user_name()) ?></span>
      <a class="btn-logout" href="logout.php">Sair</a>
    </div>
  </header>
  <main>
    <section id="app" class="container">
      <div class="card summary-grid">
        <div>
          <span class="label">Saldo</span>
          <strong id="balance">R$ 0,00</strong>
        </div>
        <div>
          <span class="label">Receitas</span>
          <strong id="income">R$ 0,00</strong>
        </div>
        <div>
          <span class="label">Despesas</span>
          <strong id="expense">R$ 0,00</strong>
        </div>
      </div>

      <form id="tx-form" class="card form-inline">
        <input type="date" id="date" required>
        <input type="text" id="desc" placeholder="Descrição" required>
        <input type="number" step="0.01" id="amount" placeholder="Valor" required>
        <select id="type">
          <option value="income">Receita</option>
          <option value="expense">Despesa</option>
        </select>
        <select id="category" required>
          <?php foreach($categories as $category): ?>
            <option value="<?php echo $category['id'] ?>"><?php echo htmlspecialchars($category['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="primary"><span class="btn-icon">＋</span>Adicionar</button>
      </form>

      <div class="card filters">
        <label>
          Tipo
          <select id="filter-type">
            <option value="all">Todos</option>
            <option value="income">Receitas</option>
            <option value="expense">Despesas</option>
          </select>
        </label>
        <label>
          Categoria
          <select id="filter-category">
            <option value="all">Todas</option>
            <?php foreach($categories as $category): ?>
              <option value="<?php echo $category['id'] ?>"><?php echo htmlspecialchars($category['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>

      <h2>Lançamentos</h2>
      <ul id="tx-list" class="list"></ul>
    </section>
  </main>

  <script src="assets/js/app.js"></script>
</body>
</html>