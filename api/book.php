<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(false, ['message' => 'POST only'], 405);

$in = json_input();
$hotelId   = (int)($in['hotel_id'] ?? 0);
$guestName = trim($in['guest_name'] ?? '');
$guestEmail = trim(strtolower($in['guest_email'] ?? ''));
$checkin   = $in['checkin'] ?? '';
$checkout  = $in['checkout'] ?? '';
$guests    = (int)($in['guests'] ?? 1);

if ($hotelId <= 0 || $guestName === '' || !filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
    respond(false, ['message' => 'Missing or invalid booking details'], 422);
}
if (!$checkin || !$checkout || strtotime($checkout) <= strtotime($checkin)) {
    respond(false, ['message' => 'Check-out date must be after check-in date'], 422);
}

$pdo = getDbConnection();

$stmt = $pdo->prepare('SELECT id, name FROM hotels WHERE id = ?');
$stmt->execute([$hotelId]);
$hotel = $stmt->fetch();
if (!$hotel) respond(false, ['message' => 'Hotel not found'], 404);

$stmt = $pdo->prepare(
    'INSERT INTO bookings (user_id, hotel_id, guest_name, guest_email, checkin, checkout, guests, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, "confirmed")'
);
$stmt->execute([current_user_id(), $hotelId, $guestName, $guestEmail, $checkin, $checkout, max(1, $guests)]);

respond(true, [
    'message' => 'Booking confirmed for ' . $hotel['name'],
    'booking_id' => (int)$pdo->lastInsertId(),
], 201);
