<?php
    session_start();
    $zamknal = $_POST["close_login"] ?? false; 
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Clicker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if($zamknal==false && !isset($_SESSION["id"])): ?>
        <div class="login-overlay" id="login-screen">
            <div class="login-box">
                <h1>Witaj w Anime Clicker!</h1>
                <p>Zgromadź miliony Yenów, rekrutuj najsilniejszych bohaterów i stań się najpotężniejszym graczem w świecie anime!</p>
                <p class="login-subtext">Aby zapisać swój postęp i zacząć klikać, zaloguj się na swoje konto.</p>
                
                <a class="login-btn" id="login-trigger" href="login.php">Zaloguj się do gry</a>
                
                <a class="register-link" href="register.php">Nie masz jeszcze konta? Zarejestruj się</a>
            </div>
            <form method="post" action="index.php">
                <input type="hidden" id="close_login" name="close_login" value="1">
                <button type="submit" class="close-btn" id="close-login">&times;</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="game-container">
        <main class="clicker-section">
            <div class="score-container">
                <h2>Yen: <span id="score-counter">0</span></h2>
            </div>
            <?php if(isset($_SESSION["id"])): ?>
                <div class="welcome-message">
                    <h2>Witaj, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
                </div>
            <?php else: ?>

            <div class="login-container-game">
                <a class="login-btn" href="login/login.html">Zaloguj się</a>
            </div>
            <?php endif; ?>
            <div class="anime-click-button" id="click-target">
                </div>
        </main>
        
        <aside class="upgrades-section">
            <h3>Sklep z ulepszeniami</h3>
            <ol class="upgrades-list" id="upgrades-container">
                </ol>
        </aside>
    </div>
    <div id="toast-notification" class="toast">Nie masz wystarczająco Yenów!</div>
    <script src="logic.js"></script>
</body>
</html>