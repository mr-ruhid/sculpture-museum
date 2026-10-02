<?php
$pdo = require __DIR__ . '/includes/db.php';

$username = 'ruhidjavadoff';
$password = '12345';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);

if ($stmt->fetch()) {
    $pdo->prepare('UPDATE users SET password_hash = ? WHERE username = ?')
        ->execute([$hash, $username]);
    echo "User updated: $username";
} else {
    $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)')
        ->execute([$username, $hash]);
    echo "User created: $username";
}