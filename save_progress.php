<?php
    session_start();
    if(!isset($_SESSION["user_id"])) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Nieautoryzowany dostęp"]);
        exit();
    }
    $conn = new mysqli("localhost", "root", "", "yurii_animeclicker");
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Błąd połączenia z bazą danych: "]);
        exit();
    }
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Brak lub uszkodzone dane wejściowe"]);
        exit();
    }

    $score = (float) ($data["score"] ?? 0);

    $upgrades = ($data['upgrades']) ?? [];

    $lvl0 = (int) ($upgrades["lvl0"] ?? 0);
    $lvl1 = (int) ($upgrades["lvl1"] ?? 0);
    $lvl2 = (int) ($upgrades["lvl2"] ?? 0);
    $lvl3 = (int) ($upgrades["lvl3"] ?? 0);

    $stmt = $conn->prepare("SELECT user_id FROM highscores WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE highscores SET yens = ?, upgrade_level_1 = ?, upgrade_level_2 = ?, upgrade_level_3 = ?, upgrade_level_4 = ? WHERE user_id = ?");
        $stmt->bind_param("diiiii", $score, $lvl0, $lvl1, $lvl2, $lvl3, $_SESSION["user_id"]);
    } else {
        $stmt = $conn->prepare("INSERT INTO highscores (user_id, yens, upgrade_level_1, upgrade_level_2, upgrade_level_3, upgrade_level_4) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idiiii", $_SESSION["user_id"], $score, $lvl0, $lvl1, $lvl2, $lvl3);
    }
    $stmt->execute();
    if($stmt->error) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Błąd podczas zapisywania postępu: " . htmlspecialchars($stmt->error)]);
        exit();
    }
    $stmt->close();
    $conn->close();
    echo json_encode(["status" => "success"]);
?>