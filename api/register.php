<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(false, ['message' => 'POST only'], 405);

$in = json_input();
$name = trim($in['name'] ?? '');
$email = trim(strtolower($in['email'] ?? ''));
$password = $in['password'] ?? '';

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    respond(false, ['message' => 'Provide a valid name, email, and a password of at least 6 characters'], 422);
}

$pdo = getDbConnection();

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    respond(false, ['message' => 'An account with that email already exists'], 409);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
$stmt->execute([$name, $email, $hash]);

$_SESSION['user_id'] = (int)$pdo->lastInsertId();
$_SESSION['user_name'] = $name;

respond(true, ['message' => 'Account created', 'user' => ['id' => $_SESSION['user_id'], 'name' => $name, 'email' => $email]]);
