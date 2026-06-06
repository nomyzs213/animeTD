<?php
require '../config_db.php';
session_start();

$komunikat = "";
$status = "";

$token = $_GET['token'] ?? '';

if (!empty($_POST["password"]) && !empty($_POST["password-repeat"])) {
    $password = $_POST["password"];
    $passwordRepeat = $_POST["password-repeat"];

    if ($password !== $passwordRepeat) {
        $status = "error";
        $komunikat = "Podane hasła nie są identyczne.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT user_id FROM email_verification_tokens WHERE token = ? AND expires_at > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("Nieprawidłowy lub wygasły token resetowania hasła.");
            }else{
                $row = $result->fetch_assoc();
                $userId = $row['user_id'];
            }
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $stmt->bind_param("si", $passwordHash, $userId);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Nie można zaktualizować hasła. Spróbuj ponownie.");
            }else{
                $stmt = $conn->prepare("DELETE FROM email_verification_tokens WHERE user_id = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
            }
            
            $status = "success";
            $komunikat = "Hasło zostało pomyślnie zmienione! Możesz się teraz zalogować.";
        } catch (Exception $e) {
            $status = "error";
            $komunikat = "Wystąpił błąd podczas zmiany hasła. Spróbuj ponownie.";
        }
    }
}else{
    $status = "error";
    $komunikat = "Wypełnij oba pola, aby ustawić nowe hasło.";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowe hasło</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #F4EAE1; /* Jasny pastelowy beż */
            color: #563F2E; /* Głęboki, ciepły brąz do tekstów */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        #change-box {
            background: #FAF6F0; /* Kremowe wnętrze karty */
            padding: 40px 45px;
            border-radius: 24px;
            border: 2px solid #E6D5C3;
            box-shadow: 0 12px 30px rgba(140, 98, 57, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            max-width: 500px;
            width: 100%;
        }
        #change-title {
            color: #8C6239; /* Ciepły brąz akcentowy */
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            text-align: center;
        }
        .change-desc {
            color: #7A6855;
            font-size: 14px;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 10px;
        }
        .change-row {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }
        .change-row label {
            font-size: 14px;
            font-weight: 600;
            color: #7A6855;
            padding-left: 5px;
        }
        .change-el {
            width: 100%;
            height: 40px;
            border-radius: 20px; /* Bąbelkowe, zaokrąglone krawędzie */
            border: 2px solid #E6D5C3;
            background-color: #FFFFFF;
            padding: 0 16px;
            font-size: 14px;
            box-sizing: border-box;
            color: #563F2E;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .change-el:focus {
            outline: none;
            border-color: #8C6239;
            box-shadow: 0 0 0 3px rgba(140, 98, 57, 0.15);
        }
        button {
            height: 46px;
            width: 240px;
            border-radius: 23px;
            margin-top: 10px;
            align-self: center;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            border: none;
            background: #8C6239;
            color: #FAF6F0;
            box-shadow: 0 4px 10px rgba(140, 98, 57, 0.15);
            transition: background 0.2s, transform 0.2s;
        }
        button:hover {
            background: #734F2D;
            transform: translateY(-1px);
        }
        button:active {
            transform: translateY(1px);
        }
        .login-link {
            margin-top: 5px;
            font-size: 0.85em;
            color: #7A6855;
        }
        .login-link a {
            color: #8C6239;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px dashed #8C6239;
            transition: color 0.2s, border-color 0.2s;
        }
        .login-link a:hover {
            color: #563F2E;
            border-bottom-style: solid;
        }
        #change-alert {
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            min-height: 18px;
            margin-top: 5px;
            padding: 8px 12px;
            border-radius: 10px;
            width: 100%;
        }
        #change-alert.success {
            background-color: #EBF3E8;
            color: #5A784E;
            border: 1px solid #A3B899;
        }
        #change-alert.error {
            background-color: #FBF0EE;
            color: #C26D5C;
            border: 1px solid #E29587;
        }
    </style>
</head>
<body>

    <form method="POST" action="" style="width: 100%; display: flex; justify-content: center;">
        <div id="change-box">
            <h1 id="change-title">Ustaw nowe hasło</h1>
            <p class="change-desc">Wprowadź i powtórz swoje nowe, bezpieczne hasło dostępowe do konta.</p>
            
            <div class="change-row">
                <label>Nowe hasło</label>
                <input type="password" class="change-el" placeholder="Minimum 8 znaków..." name="password" required>
            </div>

            <div class="change-row">
                <label>Powtórz nowe hasło</label>
                <input type="password" class="change-el" placeholder="********" name="password-repeat" required>
            </div>

            <button type="submit">Zapisz nowe hasło</button>
            
            <p class="login-link"><a href="../login/login.php">Wróć do logowania</a></p>
            
            <?php if (!empty($komunikat)): ?>
                <div id="change-alert" class="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($komunikat, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>
    </form>

</body>
</html>