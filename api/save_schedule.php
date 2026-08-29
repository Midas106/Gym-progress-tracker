<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$dow  = intval($input['dow'] ?? -1);
$name = trim($input['name'] ?? '');
if ($name === '') $name = 'Rest';

if ($dow < 0 || $dow > 6) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid day']);
    exit;
}

$stmt = $pdo->prepare("UPDATE schedule SET name = ? WHERE dow = ?");
$stmt->execute([$name, $dow]);

echo json_encode(['ok' => true]);
