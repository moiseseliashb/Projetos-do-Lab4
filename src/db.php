<?php
$config = require __DIR__ . '/config.php';
try{
  $dbCfg = $config['db'];
  $pdo = new PDO($dbCfg['dsn'], $dbCfg['user'], $dbCfg['pass']);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (strpos($dbCfg['dsn'], 'sqlite:') === 0) {
      $table = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'")->fetchColumn();
      if (!$table) {
          $sql = file_get_contents(__DIR__ . '/../sql/schema.sql');
          if ($sql !== false) {
              $pdo->exec($sql);
              $categories = ['Salário', 'Alimentação', 'Moradia', 'Transporte', 'Lazer', 'Outros'];
              $stmt = $pdo->prepare('INSERT OR IGNORE INTO categories (name) VALUES (:name)');
              foreach ($categories as $category) {
                  $stmt->execute([':name' => $category]);
              }
          }
      }
  }
}catch(PDOException $e){
  http_response_code(500);
  echo json_encode(['error' => 'DB connection failed', 'msg'=>$e->getMessage()]);
  exit;
}
