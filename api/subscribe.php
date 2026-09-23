<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(false, ['message' => 'POST only'], 405);

$in = json_input();
$email = trim(strtolower($in['email'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, ['message' => 'Enter a valid email address'], 422);
}

$pdo = getDbConnection();
try {
    $stmt = $pdo->prepare('INSERT INTO newsletter_subscribers (email) VALUES (?)');
    $stmt->execute([$email]);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        respond(true, ['message' => "You're already subscribed"]);
    }
    throw $e;
}

respond(true, ['message' => 'Subscribed! Watch your inbox for travel inspiration.']);
