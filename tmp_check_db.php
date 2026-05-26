<?php
try {
    $pdo = new PDO("sqlite:c:/wamp64/www/gestao-financeira/data/gestao.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    echo implode(',', $tables);
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
