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

        $problem = preg_replace($mathPattern, '$1', $problem);

        if (preg_match($imagePattern, $problem, $imageMatches)) {
            $problem = preg_replace($imagePattern, '<br><img class="problem-image" src="../mathproblems/'.$imageMatches[1].'"/>', $problem);
        }

        $solution = $matches[3][$i];
        $solution = preg_replace('/\\\\begin\{equation\*\}(.*?)\\\\end\{equation\*\}/s', '$1', $solution);
        $solution = preg_replace('/\\\\left\\[ /', '', $solution);
        $solution = preg_replace('/ \\\\right\\]/', '', $solution);
        $solution = trim($solution);

        $problems[] = [
            "id" => $matches[1][$i],
            "problem" => $problem,
            "solution" => $solution
        ];
    }
}

echo json_encode($problems);
?>