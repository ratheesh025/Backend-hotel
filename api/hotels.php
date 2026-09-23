<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') respond(false, ['message' => 'GET only'], 405);

$pdo = getDbConnection();

$category = $_GET['category'] ?? 'all';   // all | luxury | budget | resort
$location = trim($_GET['location'] ?? ''); // free-text search box

$sql = 'SELECT id, name, location, category, description, image_url, badge, price, rating, reviews_count FROM hotels WHERE 1=1';
$params = [];

if ($category !== 'all' && $category !== '') {
    $sql .= ' AND category = ?';
    $params[] = $category;
}
if ($location !== '') {
    $sql .= ' AND location LIKE ?';
    $params[] = '%' . $location . '%';
}
$sql .= ' ORDER BY rating DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$hotels = $stmt->fetchAll();

respond(true, ['hotels' => $hotels]);
