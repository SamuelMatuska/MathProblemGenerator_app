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
  
  document.getElementById("toggle_solution").onclick = function() {
    var solutionDiv = document.getElementById("solution");
    var toggleSolutionLink = document.getElementById("toggle_solution");
    
    if (solutionDiv.style.display === "none") {
        solutionDiv.style.display = "block";
        toggleSolutionLink.textContent = "Hide Solution";
        toggleSolutionLink.classList.add('bold');
    } else {
        solutionDiv.style.display = "none";
        toggleSolutionLink.textContent = "Show Solution";
        toggleSolutionLink.classList.add('bold');
    }
    return false;
}