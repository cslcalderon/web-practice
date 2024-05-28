<?php
$answers = [
    'q1' => 'false',
    'q2' => 'false',
    'q3' => ['b'],
    'q4' => ['c'],
    'q5' => 'http',
    'q6' => 'favicon',
];

$allQuestionsAnswered = true;
$score = 0;
$grade = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($answers as $question => $correctAnswer) {
        if (!isset($_POST[$question]) || empty(trim(is_array($_POST[$question]) ? current($_POST[$question]) : $_POST[$question]))) {
            $allQuestionsAnswered = false;
            break;
        }

        if (is_array($correctAnswer)) {
            if (isset($_POST[$question]) && is_array($_POST[$question]) && count($_POST[$question]) === 1 && strtolower(trim(current($_POST[$question]))) === strtolower($correctAnswer[0])) {
                $score++;
            }
        } else {
            if (strtolower(trim($_POST[$question])) === strtolower($correctAnswer)) {
                $score++;
            }
        }
    }

    if ($allQuestionsAnswered) {
        $grade = "Your grade is $score / " . count($answers) . ".";
        echo "<script>alert('$grade');</script>";
    } else {
        echo "<script>alert('Please answer all questions before grading.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quiz 1: Elements of Web Programming</title>
    <link rel="stylesheet" href="quiz.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

</head>

<body>
    <form method="post" id="quizForm">

        <br>
        <h1>Quiz 1</h1>
        <h1>CS 329E - Elements of Web Programming</h1>
        <h1>March 27, 2024</h1>
        <br>

        <div class="question">
            <p><strong>True / False</strong></p>
            <label>1. "URL" stands for "Universal Reference Link".</label>
            <input type="radio" name="q1" value="true"> True
            <input type="radio" name="q1" value="false"> False
        </div>

        <div class="question">
            <label>2. An Apple MacBook is an example of a Linux system.</label>
            <input type="radio" name="q2" value="true"> True
            <input type="radio" name="q2" value="false"> False
        </div>


        <div class="question">
            <p><strong>Multiple Choice</strong></p>
            <label>3. Which of these do NOT contribute to packet delay in a packet switching network?</label><br>
            <input type="checkbox" name="q3[]" value="a"> a) Processing delay at a router<br>
            <input type="checkbox" name="q3[]" value="b"> b) CPU workload on a client<br>
            <input type="checkbox" name="q3[]" value="c"> c) Transmission delay along a communications link<br>
            <input type="checkbox" name="q3[]" value="d"> d) Propagation delay<br>
        </div>

        <br>

        <div class="question">
            <label>4. This Internet layer is responsible for creating the packets that move across the
                network.</label><br>
            <input type="checkbox" name="q4[]" value="a"> a) Physical<br>
            <input type="checkbox" name="q4[]" value="b"> b) Data Link<br>
            <input type="checkbox" name="q4[]" value="c"> c) Network<br>
            <input type="checkbox" name="q4[]" value="d"> d) Transport<br>
        </div>

        <br>

        <p><strong>Fill in the Blank</strong></p>

        <div class="question">
            <label>5) ________ is a networking protocol that runs over TCP/IP, and governs communication between web
                browsers and web servers.</label>
            <input type="text" name="q5" placeholder="type answer">
        </div>

        <br>

        <div class="question">
            <label>6) A small icon displayed in a browser tab that identifies a website is called a ________.</label>
            <input type="text" name="q6" placeholder="type answer">
        </div>

        <br>


        <div class="question">
            <input type="submit" value="Grade">
            <input type="reset" value="Clear">
        </div>
    </form>

    <br>
    <br>
</body>

</html>