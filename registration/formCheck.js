
    const regButton = document.getElementsByName("reg-button");
    const regAlert = document.getElementById("reg-alert")
    regButton.addEventListener("click" , () =>{

        const username = document.getElementsByName("username").value.trim().toLowerCase();
        const email = document.getElementsByName("email").value.trim().toLowerCase();
        const password = document.getElementsByName("password").value.trim()
        const passwordRepeat = document.getElementsByName("password-repeat").value.trim()

        if(username &&email && password && passwordRepeat){

            
            if(password.length < 8 || password !== passwordRepeat || password.length > 50){
                regAlert.innerText = `hasło musi mieć przynajmniej 8 znaków i nie wiecej niż 50 
                lub hasło oraz powtórzone hasło nie są sobie równe`;
                regAlert.style.color="red";
            }

        }
    })
    
    
    
   
    

    

    





