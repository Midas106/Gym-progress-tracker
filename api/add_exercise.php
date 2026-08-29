<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$dow  = intval($input['dow'] ?? -1);
$name = trim($input['name'] ?? '');

if ($dow < 0 || $dow > 6 || $name === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$check = $pdo->prepare("SELECT id FROM exercises WHERE dow = ? AND name = ?");
$check->execute([$dow, $name]);
if ($check->fetch()) {
    echo json_encode(['ok' => true, 'duplicate' => true]);
    exit;
}

$order = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 AS n FROM exercises WHERE dow = ?");
$order->execute([$dow]);
$nextOrder = $order->fetch()['n'];

$stmt = $pdo->prepare("INSERT INTO exercises (dow, name, sort_order) VALUES (?, ?, ?)");
$stmt->execute([$dow, $name, $nextOrder]);

echo json_encode(['ok' => true, 'id' => $pdo->lastInsertId()]);
