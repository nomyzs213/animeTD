let score = parseFloat(localStorage.getItem('yenScore')) || 0;

let lvl0 = parseInt(localStorage.getItem('lvl0')) || 0; // Trening
let lvl1 = parseInt(localStorage.getItem('lvl1')) || 0; // Błogosławieństwo
let lvl2 = parseInt(localStorage.getItem('lvl2')) || 0; // Ninja
let lvl3 = parseInt(localStorage.getItem('lvl3')) || 0; // Miecz
let lvl4 = parseInt(localStorage.getItem('lvl4')) || 0; // Autoclicker
let lvl5 = parseInt(localStorage.getItem('lvl5')) || 0; // Lucky Charm (crit chance)
let lvl6 = parseInt(localStorage.getItem('lvl6')) || 0; // Discount
let lvl7 = parseInt(localStorage.getItem('lvl7')) || 0; // Golden Sword

let dodawanie = 1 + (lvl0 * 1) + (lvl3 * 5) + (lvl7 * 20);
let mnozenie = 1.0 + (lvl1 * 0.1);
let baseCps = 0 + (lvl2 * 1) + (lvl4 * 5);
let critChance = lvl5 * 0.02; // 2% per level
let costDiscount = Math.pow(0.95, lvl6); // 5% discount per level multiplicative

const baseCost0 = 10;
const baseCost1 = 100;
const baseCost2 = 500;
const baseCost3 = 1000;
const baseCost4 = 2500; // Autoclicker
const baseCost5 = 800;  // Lucky Charm
const baseCost6 = 1500; // Discount
const baseCost7 = 3000; // Golden Sword

function getCostAddings(baseCost, level) {
    return Math.max(1, Math.round(baseCost * Math.pow(3, level) * costDiscount));
}
function getCostMnozenie(baseCost, level) {
    return Math.max(1, Math.round(baseCost * Math.pow(6, level) * costDiscount));
}

const ulepszenia = [];
for (let i = 0; i < 8; i++) ulepszenia.push(document.createElement('button'));
const counter = document.getElementById('score-counter');
const clickTarget = document.getElementById('click-target');
const upgradesContainer = document.getElementById("upgrades-container");

counter.textContent = score.toFixed(2);

function updateButtonTexts() {
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

    let profit4_now = (baseCps * mnozenie).toFixed(2);
    let profit4_next = ((baseCps + 5) * mnozenie).toFixed(2);
    ulepszenia[4].textContent = `🤖 Autoclicker (${profit4_now} ➔ ${profit4_next}/sek) [Poz. ${lvl4}] | 💰 Koszt: ${getCostAddings(baseCost4, lvl4).toFixed(2)} Yen`;

    let profit5_now = Math.round(critChance * 100) + '%';
    let profit5_next = Math.round((lvl5 + 1) * 2) + '%';
    ulepszenia[5].textContent = `🍀 Lucky Charm (${profit5_now} ➔ ${profit5_next} szansa kryt.) [Poz. ${lvl5}] | 💰 Koszt: ${getCostAddings(baseCost5, lvl5).toFixed(2)} Yen`;

    let profit6_now = Math.round((1 - costDiscount) * 100) + '%';
    let profit6_next = Math.round((1 - Math.pow(0.95, lvl6 + 1)) * 100) + '%';
    ulepszenia[6].textContent = `🛍️ Discount (Obniża koszty) (${profit6_now} ➔ ${profit6_next}) [Poz. ${lvl6}] | 💰 Koszt: ${getCostAddings(baseCost6, lvl6).toFixed(2)} Yen`;

    let profit7_now = (dodawanie * mnozenie).toFixed(2);
    let profit7_next = ((dodawanie + 20) * mnozenie).toFixed(2);
    ulepszenia[7].textContent = `⚜️ Złoty Miecz (${profit7_now} ➔ ${profit7_next}/klik) [Poz. ${lvl7}] | 💰 Koszt: ${getCostAddings(baseCost7, lvl7).toFixed(2)} Yen`;
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
if (score >= 2500 || lvl4 > 0) {
    ulepszenia[4].id = 'id4';
    upgradesContainer.appendChild(ulepszenia[4]);
}
if (score >= 800 || lvl5 > 0) {
    ulepszenia[5].id = 'id5';
    upgradesContainer.appendChild(ulepszenia[5]);
}
if (score >= 1500 || lvl6 > 0) {
    ulepszenia[6].id = 'id6';
    upgradesContainer.appendChild(ulepszenia[6]);
}
if (score >= 3000 || lvl7 > 0) {
    ulepszenia[7].id = 'id7';
    upgradesContainer.appendChild(ulepszenia[7]);
}
updateButtonTexts();

clickTarget.addEventListener('click', () => {
    let gain = (dodawanie * mnozenie);
    if (Math.random() < critChance) {
        gain = gain * 2; // krytyczne trafienie daje podwójne Yeny
    }
    score += gain;
    counter.textContent = score.toFixed(2);
    localStorage.setItem("yenScore", score);
    
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
    if(score >= 2500 && !document.getElementById('id4')){
        ulepszenia[4].id = 'id4';
        upgradesContainer.appendChild(ulepszenia[4]);
    }
    if(score >= 800 && !document.getElementById('id5')){
        ulepszenia[5].id = 'id5';
        upgradesContainer.appendChild(ulepszenia[5]);
    }
    if(score >= 1500 && !document.getElementById('id6')){
        ulepszenia[6].id = 'id6';
        upgradesContainer.appendChild(ulepszenia[6]);
    }
    if(score >= 3000 && !document.getElementById('id7')){
        ulepszenia[7].id = 'id7';
        upgradesContainer.appendChild(ulepszenia[7]);
    }
    
    updateButtonTexts();
});


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

// Autoclicker
ulepszenia[4].addEventListener('click', () => {
    let currentCost = getCostAddings(baseCost4, lvl4);
    if (score >= currentCost) {
        score -= currentCost;
        lvl4++;
        baseCps += 5;

        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        localStorage.setItem("lvl4", lvl4);
        updateButtonTexts();
    } else {
        showErrorToast();
    }
});

// Lucky Charm (crit chance)
ulepszenia[5].addEventListener('click', () => {
    let currentCost = getCostAddings(baseCost5, lvl5);
    if (score >= currentCost) {
        score -= currentCost;
        lvl5++;
        critChance = lvl5 * 0.02;

        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        localStorage.setItem("lvl5", lvl5);
        updateButtonTexts();
    } else {
        showErrorToast();
    }
});

// Discount
ulepszenia[6].addEventListener('click', () => {
    let currentCost = getCostAddings(baseCost6, lvl6);
    if (score >= currentCost) {
        score -= currentCost;
        lvl6++;
        costDiscount = Math.pow(0.95, lvl6);

        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        localStorage.setItem("lvl6", lvl6);
        updateButtonTexts();
    } else {
        showErrorToast();
    }
});

// Golden Sword
ulepszenia[7].addEventListener('click', () => {
    let currentCost = getCostAddings(baseCost7, lvl7);
    if (score >= currentCost) {
        score -= currentCost;
        lvl7++;
        dodawanie += 20;

        counter.textContent = score.toFixed(2);
        localStorage.setItem("yenScore", score);
        localStorage.setItem("lvl7", lvl7);
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
            lvl3: parseInt(localStorage.getItem('lvl3')) || 0,
            lvl4: parseInt(localStorage.getItem('lvl4')) || 0,
            lvl5: parseInt(localStorage.getItem('lvl5')) || 0,
            lvl6: parseInt(localStorage.getItem('lvl6')) || 0,
            lvl7: parseInt(localStorage.getItem('lvl7')) || 0
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
    .then(async response => {
        const rawText = await response.text();
        console.log("{rawText}", rawText);
        let data = null;
        try {
            data = rawText ? JSON.parse(rawText) : null;
        } catch (e) {
            throw new Error(`Serwer zwrócił niepoprawny format danych (Kod: ${response.status})`);
        }
        if (!response.ok) {
            const serverMessage = data && data.message ? data.message : "Nieznany błąd serwera";
            const serverCode = data && data.code ? ` [Kod: ${data.code}]` : "";
            
            throw new Error(`${serverMessage}${serverCode}`);
        }

        return data;
    })
    .then(data => {
        console.log('Autozapis udany:', data);
    })
    .catch(err => {
        console.error('Komunikat błędu zapisu:', err.message);
    });
}, 15000);

    window.addEventListener('beforeunload', () => {
        const data = getGameData();
        navigator.sendBeacon('save_progress.php', data);
    });
}