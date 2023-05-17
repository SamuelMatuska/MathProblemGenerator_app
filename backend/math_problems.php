<?php
session_Start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
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
    <?php
    $pattern = '/\\\\section\*\{(.*?)\}.*?\\\\begin\{task\}(.*?)\\\\end\{task\}.*?\\\\begin\{solution\}(.*?)\\\\end\{solution\}/s';
    $imagePattern = '/\\\\includegraphics\{(.*?)\}/s';
    $mathPattern = '/\$(.*?)\$/s';
    $paths = ['../mathproblems/blokovka01pr.tex', '../mathproblems/blokovka02pr.tex', '../mathproblems/odozva01pr.tex', '../mathproblems/odozva02pr.tex'];
    $problems = [];

    foreach ($paths as $path) {
        $file = file_get_contents($path);

        preg_match_all($pattern, $file, $matches);

        for ($i = 0; $i < count($matches[0]); $i++) {
            $problem = $matches[2][$i];

            $problem = preg_replace($mathPattern, '\( $1 \)', $problem);

            if (preg_match($imagePattern, $problem, $imageMatches)) {
                $problem = preg_replace($imagePattern, '<br><img class="problem-image" src="../mathproblems/'.$imageMatches[1].'"/>', $problem);
            }
            $solution = $matches[3][$i];
            $solution = preg_replace('/\\\\begin\{equation\*\}(.*?)\\\\end\{equation\*\}/s', '$1', $solution);
            $solution = preg_replace('/\\\\left\\[ /', '', $solution);
            $solution = preg_replace('/ \\\\right\\]/', '', $solution);
            $solution = trim($solution);
            $problems[] = ["id" => $matches[1][$i], "problem" => $problem, "solution" => $solution];
        }
    }
    $randomProblem = $problems[array_rand($problems)];
    echo "<h2 id='problemid'>Problem ID: " . $randomProblem["id"] . "</h2>";
    echo "<h3 id='problemstatement'>Problem Statement: </h3>" . $randomProblem["problem"];
    ?>
    <a style="text-decoration: underline" href="https://inspera.atlassian.net/wiki/spaces/KB/pages/62062830/MathQuill+symbols" target="_blank">Documentation on how to write Math operators</a>
    <div style="padding-bottom:10px">Your answer:</div>
    <div id="answer" class="mathquill-editable"></div>
    <input type="hidden" id="correct_answer" value="<?php echo htmlspecialchars($randomProblem['solution']); ?>">
    <button id="check_button">Submit Answer</button>
    <!-- <button id="reset_button"><a href="math_problems.php">Generate new question</a></button> -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle"></h3>
            <p id="modalText"></p>
            <button id="modalButton">Generate new math problem</button>
        </div>
    </div>

    <script src="latexToJS/latex-to-js.js"></script>
    <script src="mathscript.js"></script>
</body>
</html>
