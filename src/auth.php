<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function current_user_name(): ?string {
    return $_SESSION['user_name'] ?? null;
}

function attempt_login(PDO $pdo, string $email, string $password): bool {
    $stmt = $pdo->prepare('SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($u && password_verify($password, $u['password'])) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['user_name'] = $u['name'];
        return true;
    }
    return false;
}

function register_user(PDO $pdo, string $name, string $email, string $password): bool {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, :created_at)');
    return $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $hash,
        ':created_at' => date('c')
    ]);
}
