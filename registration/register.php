<?php
require '../config.php';
require '../config_db.php';
session_start();

$komunikat = "";

try {
    
    if (!empty($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password-repeat"]) && strlen($_POST["password"]) >= 8) {
        
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["password-repeat"];

        if ($password !== $passwordRepeat) {
            $komunikat = "Podane hasła nie są identyczne.";
        } else {

            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $komunikat = "Podany email lub nazwa użytkownika jest już zajęta.";
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (email, username, password_hash) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $email, $username, $passwordHash);
                $stmt->execute();

                if ($stmt->affected_rows == 0) {
                    header("Location: ../error.php");
                    exit;
                } else {
                    try {

                        $token = bin2hex(random_bytes(32));
                        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 day'));
                        $userId = $conn->insert_id;
                        $tokenStmt = $conn->prepare("INSERT INTO email_verification_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                        $tokenStmt->bind_param("iss", $userId, $token, $expiresAt);
                        $tokenStmt->execute();

                        $mail->setFrom('animeclicker@game.com', 'Anime Clicker');
                        $mail->addAddress($email);

                        $mail->isHTML(true);                                  
                        $mail->Subject = 'Weryfikacja konta - Anime Clicker';
                        $mail->Body = '
                        <div style="background-color: #FAF6F0; padding: 40px 30px; font-family: \'Segoe UI\', Roboto, sans-serif; border-radius: 16px; max-width: 500px; margin: 0 auto; text-align: center; color: #563F2E; border: 2px solid #E6D5C3;">
                            <h1 style="color: #8C6239; margin-bottom: 15px; font-size: 26px; font-weight: 700;">Weryfikacja Konta ✨</h1>
                            <p style="font-size: 15px; line-height: 1.6; color: #7A6855; margin-bottom: 30px;">Cześć ' . htmlspecialchars($username) . '! Twoje konto w Anime Clicker zostało utworzone. Kliknij przycisk poniżej, aby aktywować profil i zalogować się do gry.</p>
                            <a href="http://localhost/clicker/animeTD/login/verified.php?token=' . $token . '" style="background-color: #8C6239 !important; color: #FAF6F0 !important; padding: 14px 32px !important; text-decoration: none !important; font-weight: bold !important; border-radius: 25px !important; display: inline-block !important; box-shadow: 0 4px 10px rgba(140, 98, 57, 0.2) !important; font-size: 15px !important;">Aktywuj konto gracza</a>
                            <hr style="border: 0; border-top: 1px solid #E6D5C3; margin: 35px 0;">
                            <p style="font-size: 11px; color: #A89685;">Jeśli to nie Ty zakładałeś konto, zignoruj tę wiadomość.</p>
                        </div>';

                        $mail->send();

                        header("Location: verify.php?status=success");
                        exit;

                    } catch (Exception $mailException) {
                        header("Location: verify.php?status=error");
                        exit;
                    } 
                }
            }
        }
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
    <title>Rejestracja</title>
    <link rel="stylesheet" href="register-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Shippori+Antique&display=swap" rel="stylesheet">
</head>
<body>

    <form method="POST" action="register.php">
        <div id="reg-box">
            <h1 id="reg-title">Rejestracja</h1>
            
            <div class="reg-row">
                <label>podaj email</label>
                <input type="email" class="reg-el" placeholder="wpisz email" name="email" required>
            </div>

            <div class="reg-row">
                <label>nazwa użytkownika</label>
                <input type="text" class="reg-el" placeholder="wpisz nazwę użytkownika" name="username" required>
            </div>

            <div class="reg-row">
                <label>podaj hasło</label>
                <input type="password" class="reg-el" placeholder="********" name="password" required>
            </div>

            <div class="reg-row">
                <label>powtórz hasło</label>
                <input type="password" class="reg-el" placeholder="********" name="password-repeat" required>
            </div>

            <button name="reg-button" type="submit">Zarejestruj się</button>
            
            <p>Masz już konto? <a href="../login/login.php">Zaloguj się</a></p>
            
            <div id="reg-alert"><?= htmlspecialchars($komunikat) ?></div>
        </div>
    </form>

    <script src="formCheck.js" type="module"></script>
    <script src="../regLogFuncs.js" type="module"></script>
</body>
</html>