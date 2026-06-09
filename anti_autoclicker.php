<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Nieautoryzowany dostęp']);
    exit();
}
try{
    $conn = new mysqli('localhost', 'root', '', 'clicker');
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Błąd serwera: nie można połączyć się z bazą danych']);
    exit();
}


$action = $_GET['action'] ?? null;
if (!$action) {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? null;
}

if (!$action) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganej akcji']);
    exit();
}

$userId = (int) $_SESSION['user_id'];

$stmt = $conn->prepare('SELECT warning_count, ban_stage, ban_expires_at FROM anti_autoclicker WHERE user_id = ?');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Błąd serwera: problem z bazą danych']);
    exit();
}
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    $insert = $conn->prepare('INSERT INTO anti_autoclicker (user_id, warning_count, ban_stage, ban_expires_at) VALUES (?, 0, 0, NULL)');
    if (!$insert) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Błąd serwera: nie można utworzyć rekordu antycheat']);
        exit();
    }
    $insert->bind_param('i', $userId);
    $insert->execute();
    $insert->close();
    $warningCount = 0;
    $banStage = 0;
    $banExpiresAt = null;
} else {
    $warningCount = (int) $row['warning_count'];
    $banStage = (int) $row['ban_stage'];
    $banExpiresAt = $row['ban_expires_at'];
}

$now = new DateTime('now', new DateTimeZone('UTC'));
$banExpiresAtDate = null;
if ($banExpiresAt) {
    $banExpiresAtDate = DateTime::createFromFormat('Y-m-d H:i:s', $banExpiresAt, new DateTimeZone('UTC'));
}

if ($banExpiresAtDate && $banExpiresAtDate > $now) {
    $remaining = $banExpiresAtDate->getTimestamp() - $now->getTimestamp();
    echo json_encode([
        'status' => 'success',
        'banned' => true,
        'warning_count' => $warningCount,
        'ban_stage' => $banStage,
        'ban_expires_at' => $banExpiresAt,
        'message' => 'Twoje konto jest zablokowane. Pozostały czas: ' . gmdate('H:i:s', $remaining)
    ]);
    $conn->close();
    exit();
}

if ($banExpiresAtDate && $banExpiresAtDate <= $now) {
    $update = $conn->prepare('UPDATE anti_autoclicker SET ban_expires_at = NULL WHERE user_id = ?');
    $update->bind_param('i', $userId);
    $update->execute();
    $update->close();
    $banExpiresAt = null;
}

if ($action === 'status') {
    echo json_encode([
        'status' => 'success',
        'banned' => false,
        'warning_count' => $warningCount,
        'ban_stage' => $banStage,
        'ban_expires_at' => null
    ]);
    $conn->close();
    exit();
}

if ($action !== 'suspicious') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Nieznana akcja']);
    $conn->close();
    exit();
}

$warningCount++;

if ($warningCount < 5) {
    $update = $conn->prepare('UPDATE anti_autoclicker SET warning_count = ? WHERE user_id = ?');
    $update->bind_param('ii', $warningCount, $userId);
    $update->execute();
    $update->close();

    echo json_encode([
        'status' => 'success',
        'banned' => false,
        'warning_count' => $warningCount,
        'warnings_left' => 5 - $warningCount,
        'block_seconds' => 10,
        'message' => 'Ostrzeżenie zapisane.'
    ]);
    $conn->close();
    exit();
}

$durations = [
    3600,
    43200,
    86400,
    604800,
    2592000
];
$banIndex = min($banStage, count($durations) - 1);
$banSeconds = $durations[$banIndex];
$banExpiresAtDate = clone $now;
$banExpiresAtDate->modify('+' . $banSeconds . ' seconds');
$banExpiresAt = $banExpiresAtDate->format('Y-m-d H:i:s');
$banStage = min($banStage + 1, count($durations));

$update = $conn->prepare('UPDATE anti_autoclicker SET warning_count = 0, ban_stage = ?, ban_expires_at = ? WHERE user_id = ?');
$update->bind_param('isi', $banStage, $banExpiresAt, $userId);
$update->execute();
$update->close();

$durationsText = [
    '1 godzina',
    '12 godzin',
    '24 godziny',
    'tydzień',
    'miesiąc'
];
$banText = $durationsText[min($banStage - 1, count($durationsText) - 1)];

echo json_encode([
    'status' => 'success',
    'banned' => true,
    'warning_count' => 0,
    'ban_stage' => $banStage,
    'ban_expires_at' => $banExpiresAt,
    'message' => 'Użytkownik zbanowany na ' . $banText . '.',
]);
$conn->close();
