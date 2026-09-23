<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') respond(false, ['message' => 'GET only'], 405);

$userId = current_user_id();
if (!$userId) respond(false, ['message' => 'Sign in to view your bookings'], 401);

$pdo = getDbConnection();
$stmt = $pdo->prepare(
    'SELECT b.id, b.checkin, b.checkout, b.guests, b.status, h.name AS hotel_name, h.location, h.image_url
     FROM bookings b JOIN hotels h ON h.id = b.hotel_id
     WHERE b.user_id = ? ORDER BY b.created_at DESC'
);
$stmt->execute([$userId]);

respond(true, ['bookings' => $stmt->fetchAll()]);
