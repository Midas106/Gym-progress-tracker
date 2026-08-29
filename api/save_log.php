<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$exercise = trim($input['exercise'] ?? '');
$weight   = floatval($input['weight'] ?? 0);
$sets     = intval($input['sets'] ?? 0);
$reps     = intval($input['reps'] ?? 0);
$reqDate  = trim($input['date'] ?? '');

if ($exercise === '' || $sets < 1 || $reps < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Exercise, sets and reps are required']);
    exit;
}

// Server's own clock is the source of truth for "today". A specific past date can be
// passed in (e.g. editing an earlier day from the calendar), but never a future one.
$today = date('Y-m-d');
if ($reqDate !== '') {
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $reqDate) || $reqDate > $today) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid date']);
        exit;
    }
    $date = $reqDate;
} else {
    $date = $today;
}

$stmt = $pdo->prepare(
    "INSERT INTO logs (exercise_name, log_date, weight, sets, reps)
     VALUES (?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE weight = VALUES(weight), sets = VALUES(sets), reps = VALUES(reps)"
);
$stmt->execute([$exercise, $date, $weight, $sets, $reps]);

echo json_encode(['ok' => true, 'date' => $date]);
