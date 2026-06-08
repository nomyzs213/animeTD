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
    $lvl4 = (int) ($upgrades["lvl4"] ?? 0);
    $lvl5 = (int) ($upgrades["lvl5"] ?? 0);
    $lvl6 = (int) ($upgrades["lvl6"] ?? 0);
    $lvl7 = (int) ($upgrades["lvl7"] ?? 0);

    $existsStmt = $conn->prepare("SELECT user_id FROM highscores WHERE user_id = ?");
    if (!$existsStmt) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Błąd serwera: nie udało się przygotować zapytania."]);
        exit();
    }
    $existsStmt->bind_param("i", $_SESSION["user_id"]);
    $existsStmt->execute();
    $result = $existsStmt->get_result();
    $rowExists = $result && $result->num_rows > 0;
    $existsStmt->close();

    if ($rowExists) {
        $stmt = $conn->prepare("UPDATE highscores SET yens = ?, upgrade_level_1 = ?, upgrade_level_2 = ?, upgrade_level_3 = ?, upgrade_level_4 = ?, upgrade_level_5 = ?, upgrade_level_6 = ?, upgrade_level_7 = ?, upgrade_level_8 = ? WHERE user_id = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Błąd serwera: nie udało się przygotować zapytania aktualizacji."]);
            exit();
        }
        $stmt->bind_param("diiiiiiiii", $score, $lvl0, $lvl1, $lvl2, $lvl3, $lvl4, $lvl5, $lvl6, $lvl7, $_SESSION["user_id"]);
    } else {
        $stmt = $conn->prepare("INSERT INTO highscores (user_id, yens, upgrade_level_1, upgrade_level_2, upgrade_level_3, upgrade_level_4, upgrade_level_5, upgrade_level_6, upgrade_level_7, upgrade_level_8) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Błąd serwera: nie udało się przygotować zapytania wstawienia."]);
            exit();
        }
        $stmt->bind_param("idiiiiiiii", $_SESSION["user_id"], $score, $lvl0, $lvl1, $lvl2, $lvl3, $lvl4, $lvl5, $lvl6, $lvl7);
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