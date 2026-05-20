<?php

$conn = new mysqli("localhost", "root", "", "clicker");

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