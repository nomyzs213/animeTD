<?php
    require '../config_db.php';
    session_start();
    if(isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }
    $komunikat = "";
    if(!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
            
            $stmt = $conn->prepare("SELECT user_id, password_hash, verified FROM users WHERE email = ? OR username = ?");
            $stmt->bind_param("ss", $email, $email);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password, $verified);
                $stmt->fetch();

                if($verified == 0) {
                    $komunikat = "Konto nie jest jeszcze zweryfikowane. Sprawdź swoją skrzynkę email.";
                } elseif(password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['registered'] = true;
                    header("Location: ../index.php");
                    exit();
                } else {
                    $komunikat = "Nieprawidłowe hasło.";
                }
            } else {
                $komunikat = "Nie znaleziono użytkownika o podanym adresie email lub nazwie użytkownika.";
            }

            $stmt->close();
            $conn->close();
        }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="log-style.css">
</head>
<body>
<form action="login.php" method="post" id="log-form">
    <div id="log-box">
        <h1 id="log-title">Logowanie</h1>
        
        <div class="log-row">
            <label>Podaj adres email lub nazwę użytkownika</label>
            <input type="text" class="log-el" name="email" placeholder="wpisz adres email lub nazwę użytkownika" id="email"> 
        </div>

        <div class="log-row">
            <label>Podaj hasło</label>
            <input type="password" class="log-el" name="password" placeholder="********" id="password"> 
        </div>

        <button id="log-btn" type="submit">Zaloguj się</button>
        
        <p> 
            <a href="../registration/register.php">Nie masz jeszcze konta? Zarejestruj się</a> 
        </p>
        <p> 
            <a href="../reset/reset_password.php">Nie pamiętasz hasła? Zresetuj je</a> 
        </p>
        <div id="logAlerts"><?= htmlspecialchars($komunikat, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>
</form>
    <script src="formCheck.js" type="module"></script>
    <script src="../regLogFuncs.js" type="module"></script>
</body>
</html>
