<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'connection.php';

// Read the math problems from the files
$basePath = '../mathproblems/';
$paths = ['blokovka01pr.tex', 'blokovka02pr.tex', 'odozva01pr.tex', 'odozva02pr.tex'];
$pattern = '/\\\\section\*\{(.*?)\}.*?\\\\begin\{task\}(.*?)\\\\end\{task\}.*?\\\\begin\{solution\}(.*?)\\\\end\{solution\}/s';
$imagePattern = '/\\\\includegraphics\{(.*?)\}/s';
$mathPattern = '/\$(.*?)\$/s';
$problems = [];

foreach ($paths as $path) {
    $folderName = $path; // Extract the name of the parent folder
    $fileContent = file_get_contents($basePath . $path);
    preg_match_all($pattern, $fileContent, $matches);

    for ($i = 0; $i < count($matches[0]); $i++) {
        $problemId = $matches[1][$i]; // Extract problem_id from \section*{BB9B40}
        $problem = $matches[2][$i];
        $problem = preg_replace($mathPattern, '\( $1 \)', $problem);

        if (preg_match($imagePattern, $problem, $imageMatches)) {
            $problem = preg_replace($imagePattern, '<br><img class="problem-image" src="mathproblems/'.$imageMatches[1].'"/>', $problem);
        }

        $solution = $matches[3][$i];
        $solution = preg_replace('/\\\\begin\{equation\*\}(.*?)\\\\end\{equation\*\}/s', '$1', $solution);
        $solution = preg_replace('/\\\\left\\[ /', '', $solution);
        $solution = preg_replace('/ \\\\right\\]/', '', $solution);
        $solution = trim($solution);

        $problems[] = [
            'folder_name' => $folderName,
            'problem_id' => $problemId, // Add problem_id to the $problems array
            'problem' => $problem,
            'solution' => $solution,
            'file_content' => $fileContent
        ];
    }
}

// Prepare and execute the SQL query to insert math problems into the table
$checkStmt = $db->prepare("SELECT COUNT(*) FROM math_problems");
$checkStmt->execute();
$rowCount = $checkStmt->fetchColumn();

if ($rowCount == 0) {
    // The math_problems table is empty, proceed with the INSERT statements

    $stmt = $db->prepare("INSERT INTO math_problems (folder_name, problem_id, problem, solution) VALUES (:folder_name, :problem_id, :problem, :solution)");
    $stmt->bindParam(":folder_name", $folder_name, PDO::PARAM_STR);
    $stmt->bindParam(":problem_id", $problem_id, PDO::PARAM_STR);
    $stmt->bindParam(":problem", $problem_text, PDO::PARAM_STR);
    $stmt->bindParam(":solution", $solution_text, PDO::PARAM_STR);

    foreach ($problems as $problem) {
        $folder_name = $problem['folder_name'];
        $problem_id = $problem['problem_id'];
        $problem_text = $problem['problem'];
        $solution_text = $problem['solution'];

        $stmt->execute();
    }
}

// Close the database connection
$db = null;

echo "Math problems uploaded successfully.";
?>
