import { validateEmail, addAlert, clearContainer } from "../regLogFuncs.js";
const regButton = document.querySelector('[name="reg-button"]');
const regAlerts = document.getElementById("reg-alert");



regButton.addEventListener("click", () => {

    clearContainer(regAlerts)

    // Używamy .querySelector('[name="..."]')
    const username = document.querySelector('[name="username"]').value.trim().toLowerCase();
    const email = document.querySelector('[name="email"]').value.trim().toLowerCase();
    const password = document.querySelector('[name="password"]').value.trim();
    const passwordRepeat = document.querySelector('[name="password-repeat"]').value.trim();
    
    
    if(username && email && password && passwordRepeat) {
        if(password.length < 8) addAlert(regAlerts , "hasło musi posiadać przynajmniej 8 znaków !");
        if(password !== passwordRepeat) addAlert(regAlerts ,  "pola z hasłami nie są sobie równe!") 
        if(!validateEmail(email)) addAlert(regAlerts , "email jest niepoprawny!");

    }
    else{
        addAlert(regAlerts , "wypełnij wszystkie pola!");
    }
});
    
    
    
   
    

    

    





