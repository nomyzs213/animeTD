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

if(!empty($_FILES))
{
    $imagename = $_FILES['pic']['name'];
    $imagetype = $_FILES['pic']['type'];
    $imageerror = $_FILES['pic']['error'];
    $imagetemp = $_FILES['pic']['tmp_name'];
    if(strlen($imagename)>240){
        $komunikat="Zbyt długa nazwa pliku!";
    }else{
        if ($imageerror !== UPLOAD_ERR_OK){
            die("Wystąpił błąd podczas przesyłania pliku! Kod błędu: " . $imageerror);
        }
        $imagePath = "images/".$imagename;
        if($imagetype!="image/jpg" && $imagetype!="image/png" && $imagetype!="image/svg+xml" && $imagetype!="image/jpeg"){
            $komunikat="Zły typ pliku!";
        }
        else{
            if(is_uploaded_file($imagetemp)) {
                if(move_uploaded_file($imagetemp, $imagePath)) {
                    $imageSTMT = $conn->prepare("SELECT imagePath FROM user_images WHERE user_id=?");
                    $imageSTMT->bind_param("i",$_SESSION["user_id"]);
                    try{
                        $imageSTMT->execute();
                        $imageSTMT->store_result();
                        $numberOfResults = $imageSTMT->num_rows();
                        if($numberOfResults==0){
                            $imageSTMT = $conn->prepare("INSERT INTO user_images (user_id, imagePath) VALUES (?,?)");
                            $imageSTMT->bind_param("is",$_SESSION["user_id"],$imagePath);
                            $imageSTMT->execute();
                        }else{
                            $imageSTMT = $conn->prepare("UPDATE user_images SET imagePath=? WHERE user_id=?");
                            $imageSTMT->bind_param("si",$imagePath,$_SESSION["user_id"]);
                            $imageSTMT->execute();
                        }
                    }catch(mysqli_sql_exception $e){
                        $komunikat="Nie udało się zapisać do bazy danych!";
                    }
                    $imageSTMT->close();
                }
                else {
                    $komunikat = "Błąd podczas zapisywania pliku!";
                }
            }
            else {
                $komunikat="Nie udało się przesłać plik!";
            }
        }
    }
}
$resetImage = $_POST['resetImage'] ?? 0;
$clickpng = "click.png";
if($resetImage){
    $imageSTMT = $conn->prepare("SELECT imagePath FROM user_images WHERE user_id=?");
    $imageSTMT->bind_param("i",$_SESSION["user_id"]);
    try{
            $imageSTMT->execute();
            $imageSTMT->store_result();
            $numberOfResults = $imageSTMT->num_rows();
        if($numberOfResults==0){
            $imageSTMT = $conn->prepare("INSERT INTO user_images (user_id, imagePath) VALUES (?,?)");
            $imageSTMT->bind_param("is",$_SESSION["user_id"],$clickpng);
            $imageSTMT->execute();
        }else{
            $imageSTMT = $conn->prepare("UPDATE user_images SET imagePath=? WHERE user_id=?");
            $imageSTMT->bind_param("si",$clickpng,$_SESSION["user_id"]);
            $imageSTMT->execute();
        }
    }catch(mysqli_sql_exception $e){
        $komunikat="Nie udało się zapisać do bazy danych!";
    }
    $imageSTMT->close();
}
if(isset($_SESSION["user_id"])){
    $stmt = $conn->prepare("SELECT imagePath FROM user_images WHERE user_id=?");
    $stmt->bind_param("i",$_SESSION["user_id"]);
    $stmt->bind_result($path);
    try{
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows()==1){
            $stmt->fetch();
            $sciezka = $path;
        }else{
            $sciezka = "click.png";
        }
    }catch(mysqli_sql_exception $e){
        $komunikat = "Problem z bazą danych";
        $sciezka = "click.png";
    }
    $stmt->close();
}else{
    $sciezka = "click.png";
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
            <img class="anime-click-button" id="click-target" src="<?php echo htmlspecialchars($sciezka,ENT_QUOTES,"UTF-8")?>">
            </img>
<?php if(isset($_SESSION["user_id"])):?>
    <form action="index.php" method="post" enctype="multipart/form-data" id="formPic" class="image-management-form">
        <label for="pic" class="custom-file-upload">
            <span>Wybierz nowy obrazek</span>
        </label>
        <input type="file" id="pic" name="pic">
    </form>
    
    <form action="index.php" method="post" class="hiddenResetImage">
        <input type="hidden" value="1" id="resetImage" name="resetImage">
        <input type="submit" value="Zresetuj obrazek" class="reset-image-btn">
    </form>
<?php endif ?>
        </main>
        
        <aside class="upgrades-section">
            <h3>Sklep z ulepszeniami</h3>
            <ol class="upgrades-list" id="upgrades-container">
                
            </ol>
        </aside>
    </div>
    <div id="toast-notification" class="toast">
        <span class="toast-message">Nie masz wystarczająco Yenów!</span>
    </div>
    <?php if(isset($_SESSION["user_id"])): ?>
        <?php
        $stmt = $conn->prepare("SELECT yens, upgrade_level_1, upgrade_level_2, upgrade_level_3, upgrade_level_4, upgrade_level_5, upgrade_level_6, upgrade_level_7, upgrade_level_8 FROM highscores WHERE user_id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $gameData = $result->fetch_assoc();
        $score = $gameData["yens"] ?? 0;
        $upgrades = [
            "lvl0" => $gameData["upgrade_level_1"] ?? 0,
            "lvl1" => $gameData["upgrade_level_2"] ?? 0,
            "lvl2" => $gameData["upgrade_level_3"] ?? 0,
            "lvl3" => $gameData["upgrade_level_4"] ?? 0,
            "lvl4" => $gameData["upgrade_level_5"] ?? 0,
            "lvl5" => $gameData["upgrade_level_6"] ?? 0,
            "lvl6" => $gameData["upgrade_level_7"] ?? 0,
            "lvl7" => $gameData["upgrade_level_8"] ?? 0
        ];
        $stmt->close();
        ?>
        <script>
            let savedScore = <?php echo htmlspecialchars((float)$score, ENT_QUOTES, 'UTF-8'); ?>;
            let lvl0_php = <?php echo htmlspecialchars((int)$upgrades["lvl0"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl1_php = <?php echo htmlspecialchars((int)$upgrades["lvl1"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl2_php = <?php echo htmlspecialchars((int)$upgrades["lvl2"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl3_php = <?php echo htmlspecialchars((int)$upgrades["lvl3"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl4_php = <?php echo htmlspecialchars((int)$upgrades["lvl4"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl5_php = <?php echo htmlspecialchars((int)$upgrades["lvl5"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl6_php = <?php echo htmlspecialchars((int)$upgrades["lvl6"], ENT_QUOTES, 'UTF-8'); ?>;
            let lvl7_php = <?php echo htmlspecialchars((int)$upgrades["lvl7"], ENT_QUOTES, 'UTF-8'); ?>;
            localStorage.setItem('yenScore', savedScore);
            localStorage.setItem('lvl0', lvl0_php);
            localStorage.setItem('lvl1', lvl1_php);
            localStorage.setItem('lvl2', lvl2_php);
            localStorage.setItem('lvl3', lvl3_php);
            localStorage.setItem('lvl4', lvl4_php);
            localStorage.setItem('lvl5', lvl5_php);
            localStorage.setItem('lvl6', lvl6_php);
            localStorage.setItem('lvl7', lvl7_php);
        </script>
    <?php endif; ?>
    <script src="logic.js"></script>
    <?php if(!empty($komunikat)):?>
        <script>
            const imageInput = document.getElementById("pic");
            imageInput.value="";
            showToast("<?= $komunikat ?>","#ff4d4d",4000);
        </script>
    <?php endif?>
</body>
</html>
<?php
    $conn->close();
?>