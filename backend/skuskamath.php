<?php
session_start();
require_once 'connection.php';

// Fetch the user's type from the database
$stmt = $db->prepare("SELECT type FROM users WHERE username = :username");
$stmt->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $userType = $user['type'];

    // Fetch a random math problem based on the user's type and folder_name
    $stmt = $db->prepare("SELECT mp.*
                          FROM math_problems mp
                          LEFT JOIN user_math_problems ump ON mp.id = ump.problem_id AND ump.user_id = :user_id
                          WHERE mp.folder_name = :folder_name AND (ump.answered_correctly IS NULL OR ump.answered_correctly = 0)
                          ORDER BY RAND() LIMIT 1");
    $stmt->bindParam(":user_id", $user['id'], PDO::PARAM_INT);
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
            // When the user submits the answer
            document.getElementById('check_button').addEventListener('click', function() {
                var userAnswer = document.getElementById('answer').textContent.trim();
                var correctAnswer = document.getElementById('correct_answer').value.trim();
                
                // Check if the user's answer is correct
                if (userAnswer === correctAnswer) {
                    document.getElementById('modalTitle').textContent = 'Correct!';
                    document.getElementById('modalText').textContent = 'Congratulations, your answer is correct!';
                    document.getElementById('myModal').style.display = 'block';

                    // Update the status in the user_math_problems table
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_user_math_problems.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            console.log('Status updated successfully.');
                        }
                    };
                    xhr.send('user_id=<?php echo $user['id']; ?>&problem_id=<?php echo $problemId; ?>&answered_correctly=true');
                } else {
                    document.getElementById('modalTitle').textContent = 'Incorrect!';
                    document.getElementById('modalText').textContent = 'Sorry, your answer is incorrect.';
                    document.getElementById('myModal').style.display = 'block';
                }
            });

            // When the user clicks the "Generate new math problem" button
            document.getElementById('modalButton').addEventListener('click', function() {
                document.getElementById('myModal').style.display = 'none';
                window.location.reload();
            });
        </script>

    <?php else : ?>
        <p>No math problems found.</p>
    <?php endif; ?>

</body>
</html>
