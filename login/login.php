<?php
    if(){
        
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
            <label>Podaj adres email</label>
            <input type="text" class="log-el" placeholder="wpisz adres email" id="username"> 
        </div>

        <div class="log-row">
            <label>Podaj hasło</label>
            <input type="password" class="log-el" placeholder="********" id="password"> 
        </div>

        <button id="log-btn" type="submit">Zaloguj się</button>
        
        <p> 
            <a href="../registration/register.html">Nie masz jeszcze konta? Zarejestruj się</a> 
        </p>
        <p> 
            <a href="../reset/reset_password.php">Nie pamiętasz hasła? Zresetuj je</a> 
        </p>
        <div id="logAlerts"></div>
    </div>
</form>
    <script src="formCheck.js" type="module"></script>
    <script src="../regLogFuncs.js" type="module"></script>
</body>
</html>
