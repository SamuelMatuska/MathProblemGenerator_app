$(document).ready(function() {
    var MQ = MathQuill.getInterface(2); // for backcompat
    var answerMathField = MQ.MathField(document.getElementById('answer'));

    function showModal(title, text) {
        $("#modalTitle").text(title);
        $("#modalText").text(text);
        $("#myModal").show();
    }

    function hideModal() {
        $("#myModal").hide();
    }

    $('#check_button').click(function() {
        var studentAnswer = answerMathField.latex();
        var correctAnswer = document.getElementById('correct_answer').value;
        
        
        studentAnswer = studentAnswer.replace(/\\frac/g, '').replace(/\\dfrac/g, '');
        correctAnswer = correctAnswer.replace(/\\frac/g, '').replace(/\\dfrac/g, '');
        correctAnswer = correctAnswer.replace(/\s/g, '');
        studentAnswer = studentAnswer.replace(/\\right\]/g, '');
        studentAnswer = studentAnswer.replace(/\\left\[/g, '');

        studentAnswer = studentAnswer.replace(/([0-9\}])([a-zA-Z])/g, '$1*$2');
        correctAnswer = correctAnswer.replace(/([0-9\}])([a-zA-Z])/g, '$1*$2');
        try {
            if (studentAnswer == correctAnswer) {
                showModal("Correct Answer!", "The answer is correct.");
                updateScores(true);
            } else {
                showModal("Incorrect Answer!", "The answer is incorrect.");
                updateScores(false);
            }
        } catch (error) {
            showModal("Error", "There was an error processing your answer. Make sure it is a valid mathematical expression.");
        }
        
        function updateScores(isCorrect) {
            $.ajax({
              url: 'update_scores.php',
              type: 'POST',
              data: {
                username: username,
                isCorrect: isCorrect ? 1 : 0
              },
              success: function(response) {
                console.log(response);
              },
              error: function(xhr, status, error) {
                console.log(error);
              }
            });
          }
    });
    $('#toggle_solution').click(function() {
        $('#solution').toggle();
    });
    $("#modalButton").click(function() {
        hideModal();
        location.reload();
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
