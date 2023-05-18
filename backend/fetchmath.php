<?php
require_once 'connection.php';

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

// Insert the problem-solution pairs into the SQL table
foreach ($problems as $problem) {
    $stmt = $db->prepare("INSERT INTO math_problems (problem_id, problem_statement, solution) VALUES (:problem_id, :problem_statement, :solution)");
    $stmt->bindParam(":problem_id", $problem['id']);
    $stmt->bindParam(":problem_statement", $problem['problem']);
    $stmt->bindParam(":solution", $problem['solution']);
    $stmt->execute();
}

// Output success message
echo "Upload successful!";

// Close the database connection
$db = null;
?>
