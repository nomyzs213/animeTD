<?php
require 'config_db.php';
session_start();
$zamknal = $_POST["close_login"] ?? false; 
$wyloguj = $_POST["logout"] ?? false;
if($wyloguj) {
    session_destroy();
    header("Location: index.php");
    exit();
}
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
    <?php if($zamknal==false && !isset($_SESSION["user_id"])): ?>
        <div class="login-overlay" id="login-screen">
            <div class="login-box">
                <h1>Witaj w Anime Clicker!</h1>
                <p>Zgromadź miliony Yenów, rekrutuj najsilniejszych bohaterów i stań się najpotężniejszym graczem w świecie anime!</p>
                <p class="login-subtext">Aby zapisać swój postęp i zacząć klikać, zaloguj się na swoje konto.</p>
                
                <a class="login-btn" id="login-trigger" href="login/login.php">Zaloguj się do gry</a>
                
                <a class="register-link" href="registration/register.php">Nie masz jeszcze konta? Zarejestruj się</a>
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
            <?php if(isset($_SESSION["user_id"])): ?>
                <form action="index.php" method="post">
                    <input type="hidden" name="logout" value="1">
                    <button type="submit" class="logout-btn">Wyloguj się</button>
                </form>
            <?php else: ?>

            <div class="login-container-game">
                <a class="login-btn" href="login/login.php">Zaloguj się</a>
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
    <?php if(isset($_SESSION["user_id"])): ?>
        <?php
        require 'config_db.php';
        $stmt = $conn->prepare("SELECT yens, upgrade_level_1, upgrade_level_2, upgrade_level_3, upgrade_level_4 FROM highscores WHERE user_id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $gameData = $result->fetch_assoc();
        $score = $gameData["yens"] ?? 0;
        $upgrades = [
            "lvl0" => $gameData["upgrade_level_1"] ?? 0,
            "lvl1" => $gameData["upgrade_level_2"] ?? 0,
            "lvl2" => $gameData["upgrade_level_3"] ?? 0,
            "lvl3" => $gameData["upgrade_level_4"] ?? 0
        ];
        $stmt->close();
        $conn->close();
        ?>
        <script>
            let savedScore = <?php echo htmlspecialchars((float)$score, ENT_QUOTES, 'UTF-8'); ?>;
            let lvl0_php = <?php echo htmlspecialchars((int)$upgrades["lvl0"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl1_php = <?php echo htmlspecialchars((int)$upgrades["lvl1"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl2_php = <?php echo htmlspecialchars((int)$upgrades["lvl2"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl3_php = <?php echo htmlspecialchars((int)$upgrades["lvl3"], ENT_QUOTES, 'UTF-8'); ?>;
            localStorage.setItem('yenScore', savedScore);
            localStorage.setItem('lvl0', lvl0_php);
            localStorage.setItem('lvl1', lvl1_php);
            localStorage.setItem('lvl2', lvl2_php);
            localStorage.setItem('lvl3', lvl3_php);
        </script>
    <?php endif; ?>
    <script src="logic.js"></script>
</body>
</html>