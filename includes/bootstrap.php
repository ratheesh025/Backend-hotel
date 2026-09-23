<?php
// Included at the top of every api/*.php file.

// If your frontend is served from a DIFFERENT domain than the API,
// replace '*' with your exact frontend origin and keep credentials true.
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'samesite' => 'Lax',
]);
session_start();

require_once __DIR__ . '/../config/database.php';

function json_input(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function respond(bool $success, $payload = [], int $code = 200): void {
    http_response_code($code);
    $body = is_array($payload) ? array_merge(['success' => $success], $payload) : ['success' => $success, 'data' => $payload];
    echo json_encode($body);
    exit;
}

function current_user_id(): ?int {
    return $_SESSION['user_id'] ?? null;
}
