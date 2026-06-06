<?php
require '../config_db.php';
session_start();
if (!isset($_SESSION["registered"])) {
    header("Location: register.php");
    exit;
}
try {

    $activationSuccess = false;
    $errorMsg = "";

    if (isset($_GET["token"])) {
        $token = $_GET["token"];

        $stmt = $conn->prepare("SELECT user_id FROM email_verification_tokens WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $userId = $row["user_id"];

            $updateStmt = $conn->prepare("UPDATE users SET verified = 1 WHERE user_id = ?");
            $updateStmt->bind_param("i", $userId);
            if ($updateStmt->execute()) {

                $deleteStmt = $conn->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
                $deleteStmt->bind_param("s", $token);
                $deleteStmt->execute();

                $_SESSION["registered"] = true;
                $_SESSION["user_id"] = $userId;

                $activationSuccess = true;
            } else {
                $errorMsg = "Nie można aktywować konta. Proszę spróbować ponownie.";
            }
        } else {
            $errorMsg = "Nieprawidłowy lub wygasły link aktywacyjny.";
        }
    } else {
        header("Location: register.php");
        exit;
    }
} catch (Exception $e) {
    header("Location: ../error.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktywacja konta</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #F4EAE1;
            color: #563F2E;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .status-container {
            background: #FAF6F0; /* Kremowe wnętrze karty */
            padding: 45px 35px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(140, 98, 57, 0.08);
            text-align: center;
            max-width: 460px;
            width: 100%;
            border: 1px solid #E6D5C3;
            border-top: 6px solid #A3B899; /* Przygaszona pastelowa zieleń dla sukcesu */
        }
        .status-container.error {
            border-top-color: #E29587; /* Przygaszony pastelowy koral dla błędu */
        }
        .icon {
            font-size: 55px;
            margin-bottom: 20px;
            display: inline-block;
        }
        h2 {
            font-size: 24px;
            color: #8C6239;
            margin-bottom: 15px;
            font-weight: 700;
        }
        p {
            color: #7A6855;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 35px;
        }
        .btn {
            display: inline-block;
            background-color: #8C6239; /* Główny akcent brązu */
            color: #FAF6F0;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 25px; /* Zaokrąglone w stylu gry clicker */
            font-weight: 600;
            font-size: 15px;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(140, 98, 57, 0.15);
        }
        .btn:hover {
            background-color: #734F2D;
            transform: translateY(-1px);
        }
        .error-box {
            background: #F3EAE1;
            color: #C26D5C;
            padding: 12px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 13px;
            margin-top: 20px;
            text-align: left;
            word-break: break-all;
            border: 1px solid #E29587;
        }
    </style>
</head>
<body>

    <?php if ($activationSuccess): ?>
        <div class="status-container">
            <div class="icon">🔓</div>
            <h2>Konto aktywowane!</h2>
            <p>Twoja weryfikacja przebiegła pomyślnie. Konto jest już w pełni aktywne, a Ty zostałeś automatycznie zalogowany do gry.</p>
            <a href="../index.php" class="btn">Wejdź do gry 🌸</a>
        </div>
    <?php else: ?>
        <div class="status-container error">
            <div class="icon">⚠️</div>
            <h2>Błąd aktywacji</h2>
            <p>Nie udało się zweryfikować Twojego konta. Link aktywacyjny może być nieaktywny lub wygasł.</p>
            <a href="../login/login.php" class="btn">Powrót</a>
            <div class="error-box">
                <strong>Szczegóły błędu:</strong><br>
                <?php echo htmlspecialchars($errorMsg); ?>
            </div>
        </div>
    <?php endif; ?>

</body>
</html>