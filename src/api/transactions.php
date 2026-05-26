<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth.php';

// exige login
if (!is_logged_in()){
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
if($method === 'GET'){
  $stmt = $pdo->prepare('SELECT t.id, t.date, t.description, t.amount, t.type, t.category_id, c.name AS category FROM transactions t JOIN categories c ON t.category_id = c.id WHERE t.user_id = :uid ORDER BY t.date DESC');
  $stmt->execute([':uid' => $userId]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($rows);
  exit;
}

if($method === 'POST'){
  $data = json_decode(file_get_contents('php://input'), true);
  if(!$data) { http_response_code(400); echo json_encode(['error'=>'Invalid JSON']); exit; }
  $stmt = $pdo->prepare('INSERT INTO transactions (user_id, category_id, date, description, amount, type) VALUES (:user_id, :category_id, :date, :description, :amount, :type)');
  $stmt->execute([
    ':user_id' => $userId,
    ':category_id' => $data['category_id'],
    ':date' => $data['date'],
    ':description' => $data['description'],
    ':amount' => $data['amount'],
    ':type' => $data['type']
  ]);
  echo json_encode(['ok'=>true, 'id' => $pdo->lastInsertId()]);
  exit;
}

http_response_code(405);
echo json_encode(['error'=>'Method not allowed']);
