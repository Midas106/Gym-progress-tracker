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

$stmt = $pdo->prepare("DELETE FROM exercises WHERE dow = ? AND name = ? LIMIT 1");
$stmt->execute([$dow, $name]);

echo json_encode(['ok' => true]);
