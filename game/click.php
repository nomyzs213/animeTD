<?php

$conn = new mysqli("localhost", "yurii_animeclickerkonto", "Jp_1234567890", "yurii_animeclicker");

$conn->query("
    UPDATE stats
    SET clicks = clicks + 1
    WHERE id = 1
");

$result = $conn->query("
    SELECT clicks
    FROM stats
    WHERE id = 1
");

$row = $result->fetch_assoc();

echo json_encode([
    "clicks" => $row["clicks"]
]);