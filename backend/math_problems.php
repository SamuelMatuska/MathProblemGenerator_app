<!DOCTYPE html>
<html>
<head>
    <!-- Load MathJax -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <link rel="stylesheet" href="../math_problems.css">
</head>
<body>
<?php
// Define the pattern to match task and solution pairs
$pattern = '/\\\\section\*\{(.*?)\}.*?\\\\begin\{task\}(.*?)\\\\end\{task\}.*?\\\\begin\{solution\}(.*?)\\\\end\{solution\}/s';

// Define the pattern to match images
$imagePattern = '/\\\\includegraphics\{(.*?)\}/s';

// Define the pattern to match LaTeX math expressions
$mathPattern = '/\$(.*?)\$/s';

// Paths to your TeX files
$paths = ['../mathproblems/blokovka01pr.tex', '../mathproblems/blokovka02pr.tex', '../mathproblems/odozva01pr.tex', '../mathproblems/odozva02pr.tex'];

// Store all problem-solution pairs
$problems = [];

foreach ($paths as $path) {
    // Read the file
    $file = file_get_contents($path);

    // Apply the pattern
    preg_match_all($pattern, $file, $matches);

    // Store results
    for ($i = 0; $i < count($matches[0]); $i++) {
        // Get the problem statement
        $problem = $matches[2][$i];

        $problem = preg_replace($mathPattern, '\( $1 \)', $problem);

        // Check for an image in the problem statement
        if (preg_match($imagePattern, $problem, $imageMatches)) {
            // If there's an image, replace the \includegraphics command with an HTML img tag
            $problem = preg_replace($imagePattern, '<br><img class="problem-image" src="../mathproblems/'.$imageMatches[1].'"/>', $problem);
        }


        // Get the solution
        $solution = $matches[3][$i];
        // Replace the equation environment with MathJax delimiters
        $solution = preg_replace('/\\\\begin\{equation\*\}(.*?)\\\\end\{equation\*\}/s', '\[ $1 \]', $solution);

        $problems[] = ["id" => $matches[1][$i], "problem" => $problem, "solution" => $solution];
    }
}

// Select a random problem
$randomProblem = $problems[array_rand($problems)];

echo "<h2>Problem ID: " . $randomProblem["id"] . "</h2>";
echo "<h3>Problem Statement: </h3>" . $randomProblem["problem"];
echo "<h3>Solution: </h3>" . $randomProblem["solution"];
?>
<button><a href="math_problems.php">Generate new question</a></button>
</body>
</html>