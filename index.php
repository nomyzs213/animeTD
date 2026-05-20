<?php
    session_start();  
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
    <?php if(isset($_SESSION["id"])):?>
        <div class="login-overlay" id="login-screen">
    <div class="login-box">
        <h1>Witaj w Anime Clicker!</h1>
        <p>Zgromadź miliony Yenów, rekrutuj najsilniejszych bohaterów i stań się najpotężniejszym graczem w świecie anime!</p>
        <p class="login-subtext">Aby zapisać swój postęp i zacząć klikać, zaloguj się na swoje konto.</p>
        <a class="login-btn" id="login-trigger" href="login.php" style="text-decoration: none;">Zaloguj się do gry</a>
    </div>
</div>
    <?php endif ?>
    <div class="game-container">
        <main class="clicker-section">
            <div class="score-container">
                <h2>Yen: <span id="score-counter">0</span></h2>
            </div>
            
            <div class="anime-click-button" id="click-target">
            </div>
        </main>
        <aside class="upgrades-section">
            <h3>Sklep z ulepszeniami</h3>
                <ol class="upgrades-list" id="upgrades-container">
                </ol>
        </aside>

    </div>
    <script>
        let score = parseInt(localStorage.getItem('yenScore')) || 0;
        let ulepszenie = 0;
        let dodawanie = 1;
        let mnozenie = 10;
        const ulepszenia = [];
        for (let i = 0; i < 4; i++) ulepszenia.push(document.createElement('button'));
        const counter = document.getElementById('score-counter');
        const clickTarget = document.getElementById('click-target');
        const upgradesContainer = document.getElementById("upgrades-container");

        clickTarget.addEventListener('click', () => {
            score+=dodawanie*mnozenie;
            counter.textContent = score;
            localStorage.setItem("yenScore",score);
            if(score>10 && ulepszenie==0){

                ulepszenia[ulepszenie].textContent = 'Ilość Yen za klik('+dodawanie+'->'+(dodawanie+1)+')';
                ulepszenia[ulepszenie].id = 'id'+ulepszenie;
                upgradesContainer.appendChild(ulepszenia[ulepszenie]);
                ulepszenie++;
            }
            if(score>100 && ulepszenie==1){
                ulepszenia[ulepszenie].textContent = 'To jest mój nowy div!';
                ulepszenia[ulepszenie].id = 'id'+ulepszenie;
                upgradesContainer.appendChild(ulepszenia[ulepszenie]);
                ulepszenie++;
            }
            if(score>1000 && ulepszenie==2){
                ulepszenia[ulepszenie].textContent = 'To jest mój nowy div!';
                ulepszenia[ulepszenie].id = 'id'+ulepszenie;
                upgradesContainer.appendChild(ulepszenia[ulepszenie]);
                ulepszenie++;
            }
            if(score>10000 && ulepszenie==3){
                ulepszenia[ulepszenie].textContent = 'To jest mój nowy div!';
                ulepszenia[ulepszenie].id = 'id'+ulepszenie;
                upgradesContainer.appendChild(ulepszenia[ulepszenie]);
                ulepszenie++;
            }
        });
    </script>
</body>
</html>