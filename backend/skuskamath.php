<?php
session_start();
require_once 'connection.php';

// Fetch the user's type and studentID from the database
$stmt = $db->prepare("SELECT type, studentID FROM users WHERE username = :username");
$stmt->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $userType = $user['type'];
    $studentID = $user['studentID'];

    // Fetch a random math problem based on the user's type and folder_name
    $stmt = $db->prepare("SELECT mp.*
                          FROM math_problems mp
                          LEFT JOIN user_math_problems ump ON mp.id = ump.problem_id AND ump.user_id = :studentID
                          WHERE mp.folder_name = :folder_name AND (ump.answered_correctly IS NULL OR ump.answered_correctly = 0)
                          ORDER BY RAND() LIMIT 1");
    $stmt->bindParam(":studentID", $studentID, PDO::PARAM_STR);
    $stmt->bindParam(":folder_name", $userType, PDO::PARAM_STR);
    $stmt->execute();

    $mathProblem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($mathProblem) {
        $problemId = $mathProblem['id'];
    }
}

?>

<html>
<head>
    <!-- Load MathJax -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <!-- Load MathQuill -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/10.4.0/math.min.js"></script>
    <link rel="stylesheet" href="../style_form.css">
    <script src="latexToJS/latex-to-js.js"></script>
    <script src="mathscript.js"></script>
    <script type='text/javascript'>
        var username = "<?php echo $_SESSION['username'] ?>";
    </script>
</head>
<body>
    <nav>
        <a href="../student.php">Student home page</a>
        <a href="math_problems.php">Exercises</a>
        <a href="logout.php">LOG OUT</a>
    </nav>

    <?php if (!empty($mathProblem)) : ?>
        <h2>Math Problem</h2>
        <div>
            <h3>Problem ID: <?php echo $mathProblem['id']; ?></h3>
            <h4>Problem Statement:</h4>
            <p><?php echo $mathProblem['problem']; ?></p>
        </div>

        <a style="text-decoration: underline" href="https://inspera.atlassian.net/wiki/spaces/KB/pages/62062830/MathQuill+symbols" target="_blank">Documentation on how to write Math operators</a>
        <div style="padding-bottom:10px">Your answer:</div>
        <div id="answer" class="mathquill-editable"></div>
        <input type="hidden" id="correct_answer" value="<?php echo htmlspecialchars($mathProblem['solution']); ?>">
        <button id="check_button">Submit Answer</button>
        <!-- <button id="reset_button"><a href="math_problems.php">Generate new question</a></button> -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <h3 id="modalTitle"></h3>
                <p id="modalText"></p>
                <button id="modalButton">Generate new math problem</button>
            </div>
        </div>

        <script>
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
      var problemId = <?php echo $problemId; ?>;

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
            problemId: problemId,
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

    $('#modalButton').click(function() {
      hideModal();
      location.reload();
    });
  });
</script>

    <?php else : ?>
        <p>No math problems found.</p>
    <?php endif; ?>

</body>
</html>
