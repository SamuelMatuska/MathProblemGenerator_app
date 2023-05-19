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
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="../final.css">

    <script type='text/javascript'>
        var username = "<?php echo $_SESSION['username'] ?>";
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Math Gen app</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="student_sk.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="readme_student_sk.php">Návod</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="math_problems_sk.php">Príklady</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../backend/logout.php">Odhlásiť sa</a>
                        </li>
                        <li class="nav-item">
                            <a href="../readme_student.php">
                                <img src="../Flag_of_the_United_Kingdom.svg" alt="English Flag" style="height:30px; width:45px;">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
    </nav>
    <div id="container">
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
    echo "<h2 id='problemid'>Problém ID: " . $randomProblem["id"] . "</h2>";
    echo "<h3 id='problemstatement'>Problém: </h3>" . $randomProblem["problem"];
    ?>
    <a style="text-decoration:underline ; color:black" href="https://inspera.atlassian.net/wiki/spaces/KB/pages/62062830/MathQuill+symbols" target="_blank">Documentation on how to write Math operators</a>
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
</div>

    <script src="../backend/mathscript.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
