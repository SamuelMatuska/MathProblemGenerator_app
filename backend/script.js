document.addEventListener("DOMContentLoaded", function() {
    var signInButton = document.getElementById("signIn");
    var signUpButton = document.getElementById("signUp");
    const container = document.getElementById('container');
  
    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });
    
    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });

  }); 