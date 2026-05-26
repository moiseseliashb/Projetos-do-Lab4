<?php
// Script simples para inicializar o DB (SQLite) e criar um usuário admin
require_once __DIR__ . '/config.php';
$config = require __DIR__ . '/config.php';
$dbCfg = $config['db'];
try{
  if (!in_array('sqlite', PDO::getAvailableDrivers())) {
    throw new Exception('O driver PDO_SQLITE não está habilitado no seu PHP. Instale ou habilite o PDO_SQLITE antes de continuar.');
  }
  $pdo = new PDO($dbCfg['dsn'], $dbCfg['user'], $dbCfg['pass']);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = file_get_contents(__DIR__ . '/../sql/schema.sql');
  $pdo->exec($sql);
  $categories = ['Salário', 'Alimentação', 'Moradia', 'Transporte', 'Lazer', 'Outros'];
  $stmt = $pdo->prepare('INSERT OR IGNORE INTO categories (name) VALUES (:name)');
  foreach ($categories as $category) {
      $stmt->execute([':name' => $category]);
  }
  // criar usuário admin se não existir
  $email = 'admin@local';
  $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
  $stmt->execute([':email' => $email]);
  if (!$stmt->fetch()){
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $i = $pdo->prepare('INSERT INTO users (name,email,password,created_at) VALUES (:name,:email,:password,:created_at)');
    $i->execute([':name'=>'Admin',':email'=>$email,':password'=>$hash,':created_at'=>date('c')]);
    echo "Usuário admin criado: admin@local / admin123\n";
  } else {
    echo "Usuário admin já existe.\n";
  }
  echo "DB inicializado com sucesso.\n";
}catch(PDOException $e){
  echo "Erro: " . $e->getMessage() . "\n";
}
