// formCheck.js
export function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

export function addAlert(container, textToAdd) { 
    const alert = document.createElement("p");
    alert.textContent = textToAdd;
    container.appendChild(alert);
}

export function clearContainer(container) {
    container.innerHTML = "";
}