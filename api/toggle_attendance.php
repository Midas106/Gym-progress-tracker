<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$date = trim($input['date'] ?? '');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date']);
    exit;
}

$check = $pdo->prepare("SELECT log_date FROM attendance WHERE log_date = ?");
$check->execute([$date]);

if ($check->fetch()) {
    $stmt = $pdo->prepare("DELETE FROM attendance WHERE log_date = ?");
    $stmt->execute([$date]);
    echo json_encode(['ok' => true, 'marked' => false]);
} else {
    $stmt = $pdo->prepare("INSERT INTO attendance (log_date) VALUES (?)");
    $stmt->execute([$date]);
    echo json_encode(['ok' => true, 'marked' => true]);
}
