<?php
$config = require __DIR__ . '/config.php';
try{
  $dbCfg = $config['db'];
  $pdo = new PDO($dbCfg['dsn'], $dbCfg['user'], $dbCfg['pass']);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  http_response_code(500);
  echo json_encode(['error' => 'DB connection failed', 'msg'=>$e->getMessage()]);
  exit;
}
