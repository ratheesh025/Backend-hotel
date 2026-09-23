<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(false, ['message' => 'POST only'], 405);

$in = json_input();
$email = trim(strtolower($in['email'] ?? ''));
$password = $in['password'] ?? '';

$pdo = getDbConnection();
$stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    respond(false, ['message' => 'Invalid email or password'], 401);
}

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['user_name'] = $user['name'];

respond(true, ['message' => 'Signed in', 'user' => ['id' => $user['id'], 'name' => $user['name'], 'email' => $email]]);
