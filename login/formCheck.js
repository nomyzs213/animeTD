import { validateEmail, addAlert, clearContainer } from "../regLogFuncs.js";

const submitBtn = document.getElementById("log-btn");
const logAlerts = document.getElementById("logAlerts");

submitBtn.addEventListener("click", () => {
    const email = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    clearContainer(logAlerts);

    if (email && password) {
        if (password.length < 8) addAlert(logAlerts, "Hasło musi mieć co najmniej 8 znaków");
    } else {
        addAlert(logAlerts, "Wypełnij wszystkie pola");
    }
});