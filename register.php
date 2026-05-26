<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($name === '' || $email === '' || $password === '') {
        $errors[] = 'Preencha todos os campos.';
    } else {
        // checar se email existe
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $errors[] = 'E-mail já cadastrado.';
        } else {
            register_user($pdo, $name, $email, $password);
            attempt_login($pdo, $email, $password);
            header('Location: index.php'); exit;
        }
    }
} 
?><!doctype html>
<html lang="pt-br" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Cadastro — Gestão Financeira</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="topbar auth-topbar">
    <div class="brand">
      <span class="brand-icon">✍️</span>
      <div>
        <strong>Gestão Financeira</strong>
        <span class="brand-subtitle">Crie sua conta</span>
      </div>
    </div>
    <button id="theme-toggle" aria-label="Alternar tema">🌓</button>
  </header>
  <main class="auth-page">
    <div class="auth-layout">
      <section class="auth-panel">
        <div class="auth-image">
          <img src="assets/img/Investment%20data-amico.png" alt="Ilustração de finanças">
          <div class="auth-copy">
            <h1>Comece sua jornada</h1>
            <p>Cadastre-se para organizar suas finanças e controlar entradas e saídas em um painel simples e visual.</p>
          </div>
        </div>
      </section>
      <section class="auth-card card">
      <div class="auth-header">
        <div class="icon-circle">🧾</div>
        <h1>Cadastrar</h1>
      </div>
      <?php if($errors): ?><div class="alert"><?php echo htmlspecialchars($errors[0]) ?></div><?php endif; ?>
      <form method="post" class="auth-form">
        <label><span>Nome</span><input name="name" type="text" placeholder="Seu nome" required></label>
        <label><span>E-mail</span><input name="email" type="email" placeholder="seu@exemplo.com" required></label>
        <label><span>Senha</span><input name="password" type="password" placeholder="••••••••" required></label>
        <label><span>Tipo de conta</span><select name="account_type" required><option value="personal">Pessoal</option><option value="business">Profissional</option></select></label>
        <label class="checkbox"><input type="checkbox" name="newsletter"> Quero receber dicas financeiras</label>
        <button type="submit" class="primary">Criar conta</button>
      </form>
      <p class="small">Já tem conta? <a href="login.php">Entrar</a></p>
      </section>
    </div>
  </main>
  <script src="assets/js/app.js"></script>
</body>
</html>