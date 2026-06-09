<?php

$conn = new mysqli("localhost", "root", "", "clicker");

// Ensure `stats` table exists and there is a row with id=1
$conn->query("CREATE TABLE IF NOT EXISTS stats (id INT PRIMARY KEY, clicks BIGINT NOT NULL DEFAULT 0)");
$conn->query("INSERT INTO stats (id, clicks) SELECT 1, 0 WHERE NOT EXISTS (SELECT 1 FROM stats WHERE id = 1)");

$conn->query("UPDATE stats SET clicks = clicks + 1 WHERE id = 1");

$result = $conn->query("SELECT clicks FROM stats WHERE id = 1");

$row = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode([
    "clicks" => (int)$row["clicks"]
]);
