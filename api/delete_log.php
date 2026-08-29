<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$exercise = trim($input['exercise'] ?? '');
$date     = trim($input['date'] ?? '');

if ($exercise === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM logs WHERE exercise_name = ? AND log_date = ?");
$stmt->execute([$exercise, $date]);

echo json_encode(['ok' => true]);
