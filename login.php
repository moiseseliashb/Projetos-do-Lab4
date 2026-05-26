<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (attempt_login($pdo, $email, $password)) {
        header('Location: /'); exit;
    } else {
        $errors[] = 'E-mail ou senha inválidos.';
    }
}
?><!doctype html>
<html lang="pt-br" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login — Gestão Financeira</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <header class="topbar auth-topbar">
    <div class="brand">
      <span class="brand-icon">🔐</span>
      <div>
        <strong>Gestão Financeira</strong>
        <span class="brand-subtitle">Acesse sua conta</span>
      </div>
    </div>
    <button id="theme-toggle" aria-label="Alternar tema">🌓</button>
  </header>
  <main class="auth-page">
    <div class="auth-layout">
      <section class="auth-panel">
        <div class="auth-image">
          <img src="/assets/img/Investment%20data-amico.png" alt="Ilustração de finanças">
          <div class="auth-copy">
            <h1>Bem-vindo de volta</h1>
            <p>Entre na sua conta para acompanhar suas finanças, adicionar despesas e ver relatórios em um painel intuitivo.</p>
          </div>
        </div>
      </section>
      <section class="auth-card card">
      <div class="auth-header">
        <div class="icon-circle">👤</div>
        <h1>Entrar</h1>
      </div>
      <?php if($errors): ?><div class="alert"><?php echo htmlspecialchars($errors[0]) ?></div><?php endif; ?>
      <form method="post" class="auth-form">
        <label><span>E-mail</span><input name="email" type="email" placeholder="seu@exemplo.com" required></label>
        <label><span>Senha</span><input name="password" type="password" placeholder="••••••••" required></label>
        <button type="submit" class="primary">Entrar</button>
      </form>
      <p class="small">Não tem conta? <a href="/register.php">Cadastre-se</a></p>
      </section>
    </div>
  </main>
  <script src="/assets/js/app.js"></script>
</body>
</html>