<?php
require '../config.php';
require '../config_db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../game/index.php");
    exit;
}

$komunikat = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["user_identifier"])) {
    try {
        $identifier = $_POST["user_identifier"];

        $stmt = $conn->prepare("SELECT user_id, email FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $userId = $row["user_id"];
            $email = $row["email"];

            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt = $conn->prepare("INSERT INTO email_verification_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $token, $expiresAt);
            $stmt->execute();
            if(!$stmt->affected_rows) {
                throw new Exception("Nie można wygenerować tokena resetującego. Spróbuj ponownie.");
            }
            

            $mail->setFrom('ukhman.yuriy@gmail.com', 'Anime Clicker');
            $mail->addAddress($email);
            $mail->isHTML(true);

            $mail->Subject = 'Instrukcje resetowania hasła do Anime Clicker';
            $resetLink = "http://localhost/clicker/reset/new_password.php?token=$token";
            $mail->Body    = '
                <div style="background-color: #FAF6F0; padding: 40px 30px; font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif; border-radius: 16px; max-width: 500px; margin: 0 auto; text-align: center; color: #563F2E; border: 2px solid #E6D5C3;">
    
                <div style="font-size: 55px; margin-bottom: 15px;">🔑</div>
                
                <h1 style="color: #8C6239; margin-bottom: 15px; font-size: 26px; font-weight: 700;">Resetowanie Hasła</h1>
                
                <p style="font-size: 15px; line-height: 1.6; color: #7A6855; margin-bottom: 30px;">
                    Witaj! Otrzymaliśmy zgłoszenie o chęci zmiany hasła do Twojego konta w <b>Anime Clicker</b>. Kliknij poniższy przycisk, aby bezpiecznie ustawić nowe hasło. Link będzie ważny przez 1 godzinę.
                </p>
                
                <a href="' . $resetLink . '" style="background-color: #8C6239 !important; color: #FAF6F0 !important; padding: 14px 32px !important; text-decoration: none !important; font-weight: bold !important; border-radius: 25px !important; display: inline-block !important; box-shadow: 0 4px 10px rgba(140, 98, 57, 0.2) !important; font-size: 15px !important; letter-spacing: 0.5px !important;">Zresetuj hasło</a>
                
                <hr style="border: 0; border-top: 1px solid #E6D5C3; margin: 35px 0;">
                
                <p style="font-size: 11px; color: #A89685; line-height: 1.4;">
                    Jeśli to nie Ty prosiłeś o resetowanie hasła, po prostu zignoruj tę wiadomość – Twoje obecne hasło pozostanie bezpieczne.
                </p>
                
                <p style="font-size: 13px; color: #7A6855; margin-top: 20px; font-weight: 600;">
                    Pozdrawiamy,<br><span style="color: #8C6239;">Zespół Anime Clicker</span>
                </p>
                
            </div>
            ';
            $mail->AltBody = "Odzyskiwanie hasła do Anime Clicker\n\nWitaj,\n\nKliknij poniższy link, aby zresetować swoje hasło. Link będzie ważny przez 1 godzinę.\n\n$resetLink\n\nJeśli nie prosiłeś o resetowanie hasła, zignoruj tę wiadomość.\n\nPozdrawiamy,\nZespół Anime Clicker";
            $mail->send();
            $status = "success";
            $komunikat = "Instrukcje resetowania hasła zostały wysłane na powiązany adres e-mail.";
        } else {
            $status = "error";
            $komunikat = "Nie znaleziono użytkownika o podanym loginie lub adresie e-mail.";
        }
    } catch (Exception $e) {
        $status = "error";
        $komunikat = "Wystąpił błąd systemu. Spróbuj ponownie później.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetowanie hasła</title>
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
        #reset-box {
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
        #reset-title {
            color: #8C6239; /* Ciepły brąz akcentowy */
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            text-align: center;
        }
        .reset-desc {
            color: #7A6855;
            font-size: 14px;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 10px;
        }
        .reset-row {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }
        .reset-row label {
            font-size: 14px;
            font-weight: 600;
            color: #7A6855;
            padding-left: 5px;
        }
        .reset-el {
            width: 100%;
            height: 40px;
            border-radius: 20px; /* Zaokrąglone krawędzie pasujące do reszty panelu */
            border: 2px solid #E6D5C3;
            background-color: #FFFFFF;
            padding: 0 16px;
            font-size: 14px;
            box-sizing: border-box;
            color: #563F2E;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .reset-el:focus {
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
        .back-link {
            margin-top: 5px;
            font-size: 0.85em;
            color: #7A6855;
        }
        .back-link a {
            color: #8C6239;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px dashed #8C6239;
            transition: color 0.2s, border-color 0.2s;
        }
        .back-link a:hover {
            color: #563F2E;
            border-bottom-style: solid;
        }
        #reset-alert {
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            min-height: 18px;
            margin-top: 5px;
            padding: 8px 12px;
            border-radius: 10px;
            width: 100%;
        }
        #reset-alert.success {
            background-color: #EBF3E8;
            color: #5A784E;
            border: 1px solid #A3B899;
        }
        #reset-alert.error {
            background-color: #FBF0EE;
            color: #C26D5C;
            border: 1px solid #E29587;
        }
    </style>
</head>
<body>

    <form method="POST" action="reset_password.php" style="width: 100%; display: flex; justify-content: center;">
        <div id="reset-box">
            <h1 id="reset-title">Odzyskiwanie konta</h1>
            <p class="reset-desc">Wprowadź swój adres e-mail lub nazwę użytkownika, a prześlemy Ci instrukcje resetowania hasła.</p>
            
            <div class="reset-row">
                <label>E-mail lub nazwa użytkownika</label>
                <input type="text" class="reset-el" placeholder="Wpisz e-mail lub login..." name="user_identifier" required>
            </div>

            <button type="submit">Wyślij link resetujący</button>
            
            <p class="back-link">Przypomniałeś sobie? <a href="../login/login.php">Wróć do logowania</a></p>
            
            <?php if (!empty($komunikat)): ?>
                <div id="reset-alert" class="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($komunikat, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>
    </form>

</body>
</html>