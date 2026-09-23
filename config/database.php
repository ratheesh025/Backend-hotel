<?php
// Database connection settings — fill these in for your hosting environment.
// Never commit real credentials to a public repo; use environment variables
// on production if your host supports them (getenv('DB_PASS') etc).

define('DB_HOST', 'localhost');
define('DB_NAME', 'stayease');
define('DB_USER', 'stayease_user');
define('DB_PASS', 'CHANGE_ME');

function getDbConnection(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Database connection failed']);
            exit;
        }
    }
    return $pdo;
}
