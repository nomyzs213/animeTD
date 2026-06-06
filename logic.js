// Zmiana na parseFloat, aby zachować grosze przy wczytywaniu
let score = parseFloat(localStorage.getItem('yenScore')) || 0;

// Zmienne przechowujące liczbę zakupionych ulepszeń (wczytywane z localStorage)
let lvl0 = parseInt(localStorage.getItem('lvl0')) || 0; // Trening
let lvl1 = parseInt(localStorage.getItem('lvl1')) || 0; // Błogosławieństwo
let lvl2 = parseInt(localStorage.getItem('lvl2')) || 0; // Ninja
let lvl3 = parseInt(localStorage.getItem('lvl3')) || 0; // Miecz

// Dynamiczne przeliczanie statystyk na start na podstawie wczytanych poziomów
let dodawanie = 1 + (lvl0 * 1) + (lvl3 * 5);
let mnozenie = 1.0 + (lvl1 * 0.1);
let baseCps = 0 + (lvl2 * 1);

// Ceny bazowe ulepszeń
const baseCost0 = 10;
const baseCost1 = 100;
const baseCost2 = 500;
const baseCost3 = 1000;

function getCostAddings(baseCost, level) {
    return Math.round(baseCost * Math.pow(3, level));
}
function getCostMnozenie(baseCost, level) {
    return Math.round(baseCost * Math.pow(6, level));
}

const ulepszenia = [];
for (let i = 0; i < 4; i++) ulepszenia.push(document.createElement('button'));
const counter = document.getElementById('score-counter');
const clickTarget = document.getElementById('click-target');
const upgradesContainer = document.getElementById("upgrades-container");

// Wyświetlenie wyniku z dwoma miejscami po przecinku na start
counter.textContent = score.toFixed(2);

function updateButtonTexts() {
    // Poprawione wywołania funkcji kosztów (getCostAddings / getCostMnozenie) oraz dodane .toFixed(2)
    let profit0_now = (dodawanie * mnozenie).toFixed(2);
    let profit0_next = ((dodawanie + 1) * mnozenie).toFixed(2);
    ulepszenia[0].textContent = `🏋️ Trening na siłowni (${profit0_now} ➔ ${profit0_next}/klik) [Poz. ${lvl0}] | 💰 Koszt: ${getCostAddings(baseCost0, lvl0).toFixed(2)} Yen`;

    let profit1_now = (mnozenie * 100).toFixed(0);
    let profit1_next = ((mnozenie + 0.1) * 100).toFixed(0);
    ulepszenia[1].textContent = `✨ Błogosławieństwo (+${profit1_now}% ➔ +${profit1_next}% globalnie) [Poz. ${lvl1}] | 💰 Koszt: ${getCostMnozenie(baseCost1, lvl1).toFixed(2)} Yen`;

    let profit2_now = (baseCps * mnozenie).toFixed(2);
    let profit2_next = ((baseCps + 1) * mnozenie).toFixed(2);
    ulepszenia[2].textContent = `🥷 Początkujący Ninja (${profit2_now} ➔ ${profit2_next}/sek) [Poz. ${lvl2}] | 💰 Koszt: ${getCostAddings(baseCost2, lvl2).toFixed(2)} Yen`;

    let profit3_now = (dodawanie * mnozenie).toFixed(2);
    let profit3_next = ((dodawanie + 5) * mnozenie).toFixed(2);
    ulepszenia[3].textContent = `⚔️ Miecz z czarnej stali (${profit3_now} ➔ ${profit3_next}/klik) [Poz. ${lvl3}] | 💰 Koszt: ${getCostAddings(baseCost3, lvl3).toFixed(2)} Yen`;
}

// Sprawdzenie na start (gdy gracz odświeża stronę z wczytanym już stanem)
if (score >= 0) {
    ulepszenia[0].id = 'id0';
    upgradesContainer.appendChild(ulepszenia[0]);
}
if (score >= 100 || lvl1 > 0) {
    ulepszenia[1].id = 'id1';
    upgradesContainer.appendChild(ulepszenia[1]);
}
if (score >= 500 || lvl2 > 0) {
    ulepszenia[2].id = 'id2';
    upgradesContainer.appendChild(ulepszenia[2]);
}
if (score >= 1000 || lvl3 > 0) {
    ulepszenia[3].id = 'id3';
    upgradesContainer.appendChild(ulepszenia[3]);
}
updateButtonTexts();

clickTarget.addEventListener('click', () => {
    score += (dodawanie * mnozenie);
    counter.textContent = score.toFixed(2);
    localStorage.setItem("yenScore", score);
    
    // Logika pojawiania się przycisków
    if(score >= 100 && !document.getElementById('id1')){
        ulepszenia[1].id = 'id1';
        upgradesContainer.appendChild(ulepszenia[1]);
    }
    if(score >= 500 && !document.getElementById('id2')){
        ulepszenia[2].id = 'id2';
        upgradesContainer.appendChild(ulepszenia[2]);
    }
    if(score >= 1000 && !document.getElementById('id3')){
        ulepszenia[3].id = 'id3';
        upgradesContainer.appendChild(ulepszenia[3]);
    }
    
    updateButtonTexts();
});

// LOGIKA ZAKUPU ULEPSZEŃ

ulepszenia[0].addEventListener('click', () => {
    let currentCost = getCostAddings(baseCost0, lvl0);
    if (score >= currentCost) {
        score -= currentCost;
        lvl0++; 
        dodawanie += 1;
        
        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        localStorage.setItem("lvl0", lvl0); 
        updateButtonTexts();
    } else {
        showErrorToast();
    }
});

ulepszenia[1].addEventListener('click', () => {
    let currentCost = getCostMnozenie(baseCost1, lvl1);
    if (score >= currentCost) {
        score -= currentCost;
        lvl1++;
        mnozenie += 0.1;
        
        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        localStorage.setItem("lvl1", lvl1);
        updateButtonTexts();
    } else {
        showErrorToast();
    }
});

ulepszenia[2].addEventListener('click', () => {
    let currentCost = getCostAddings(baseCost2, lvl2);
    if (score >= currentCost) {
        score -= currentCost;
        lvl2++;
        baseCps += 1;
        
        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        localStorage.setItem("lvl2", lvl2);
        updateButtonTexts();
    } else {
        showErrorToast();
    }
});

ulepszenia[3].addEventListener('click', () => {
    let currentCost = getCostAddings(baseCost3, lvl3);
    if (score >= currentCost) {
        score -= currentCost;
        lvl3++;
        dodawanie += 5;
        
        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        localStorage.setItem("lvl3", lvl3);
        updateButtonTexts();
    } else {
        showErrorToast();
    }
});
function showErrorToast() {
    const toast = document.getElementById('toast-notification');
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
setInterval(() => {
    if (baseCps > 0) {
        score += (baseCps * mnozenie); 
        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        updateButtonTexts(); 
    }
}, 1000);


const isUserLoggedIn = document.querySelector('.logout-btn') !== null;

if (isUserLoggedIn) {
    function getGameData() {
        const score = parseFloat(localStorage.getItem('yenScore')) || 0;
        
        const upgrades = {
            lvl0: parseInt(localStorage.getItem('lvl0')) || 0,
            lvl1: parseInt(localStorage.getItem('lvl1')) || 0,
            lvl2: parseInt(localStorage.getItem('lvl2')) || 0,
            lvl3: parseInt(localStorage.getItem('lvl3')) || 0
        };

        return JSON.stringify({
            score: score,
            upgrades: upgrades
        });
    }
    setInterval(() => {
        fetch('save_progress.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: getGameData()
        })
        .then(response => response.json())
        .then(data => console.log('Autozapis bazy danych:', data))
        .catch(err => console.error('Błąd zapisu:', err));
    }, 15000);

    window.addEventListener('beforeunload', () => {
        const data = getGameData();
        navigator.sendBeacon('save_progress.php', data);
    });
}