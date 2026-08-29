<?php
header('Content-Type: application/json');
require 'db.php';

$scheduleRows = $pdo->query("SELECT dow, name FROM schedule ORDER BY dow")->fetchAll();
$schedule = [];
foreach ($scheduleRows as $row) {
    $schedule[$row['dow']] = ['name' => $row['name'], 'exercises' => []];
}

$exRows = $pdo->query("SELECT dow, name FROM exercises ORDER BY sort_order, id")->fetchAll();
foreach ($exRows as $row) {
    if (isset($schedule[$row['dow']])) {
        $schedule[$row['dow']]['exercises'][] = $row['name'];
    }
}

$logRows = $pdo->query("SELECT exercise_name AS exercise, log_date AS date, weight, sets, reps FROM logs ORDER BY log_date")->fetchAll();
foreach ($logRows as &$l) {
    $l['weight'] = floatval($l['weight']);
    $l['sets']   = intval($l['sets']);
    $l['reps']   = intval($l['reps']);
}
unset($l);

$attendanceRows = $pdo->query("SELECT log_date FROM attendance")->fetchAll();
$attendance = array_map(fn($r) => $r['log_date'], $attendanceRows);

echo json_encode(['schedule' => $schedule, 'logs' => $logRows, 'attendance' => $attendance]);
