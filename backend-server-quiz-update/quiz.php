<?php
session_start();

if (!isset($_SESSION['username']) || time() > $_SESSION['expire_time']) {
    if (isset($_SESSION['score'])) {
        $resultString = $_SESSION['username'] . ":" . $_SESSION['score'] . "\n"; // Save the current score
    } else {
        $_SESSION['score'] = 0;
        $resultString = $_SESSION['username'] . ":" . $_SESSION['score'] . "\n";
    }
    file_put_contents("results.txt", $resultString, FILE_APPEND | LOCK_EX);
    session_destroy();
    header('Location: login.php');
    exit;
}

//ques and ans all the info
$questions = [
    ['q' => '"URL" stands for "Universal Reference Link".', 'a' => 'false', 'type' => 'tf'],
    ['q' => 'An Apple MacBook is an example of a Linux system.', 'a' => 'true', 'type' => 'tf'],
    ['q' => 'Which of these do NOT contribute to packet delay in a packet switching network?', 'a' => ['b'], 'type' => 'mc'],
    ['q' => 'This Internet layer is responsible for creating the packets that move across the network.', 'a' => ['c'], 'type' => 'mc'],
    ['q' => '________ is a networking protocol that runs over TCP/IP, and governs communication between web browsers and web servers.', 'a' => 'http', 'type' => 'fitb'],
    ['q' => 'A small icon displayed in a browser tab that identifies a website is called a ________.', 'a' => 'favicon', 'type' => 'fitb']
];

$currentQuestion = $_SESSION['current_question'] ?? 0;
$score = $_SESSION['score'] ?? 0;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $givenAnswer = $_POST['answer'];
    $correctAnswer = $questions[$currentQuestion]['a'];

    //check based on tpe

    if ($questions[$currentQuestion]['type'] === 'mc') {
        if (isset($_POST['answer']) && is_array($_POST['answer']) && !array_diff($_POST['answer'], $correctAnswer) && !array_diff($correctAnswer, $_POST['answer'])) {
            $score += 10;
        }
    } elseif ($questions[$currentQuestion]['type'] === 'tf' || $questions[$currentQuestion]['type'] === 'fitb') {
        if (strtolower(trim($givenAnswer)) === strtolower($correctAnswer)) {
            $score += 10;
        }
    }

    $_SESSION['current_question'] = ++$currentQuestion;
    $_SESSION['score'] = $score;

    if ($currentQuestion >= count($questions)) {
        $resultString = $_SESSION['username'] . ":" . $score . "\n";
        file_put_contents("results.txt", $resultString, FILE_APPEND | LOCK_EX);
        session_destroy();
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="quiz.css">
    <title>Quiz</title>
</head>

<body>
    <h1>Question <?php echo $currentQuestion + 1; ?> of <?php echo count($questions); ?></h1>
    <form method="POST">
        <p><?php echo $questions[$currentQuestion]['q']; ?></p>
        <?php
        if ($questions[$currentQuestion]['type'] === 'tf') {
            echo '<input type="radio" name="answer" value="true"> True';
            echo '<input type="radio" name="answer" value="false"> False';
        } elseif ($questions[$currentQuestion]['type'] === 'mc') {
            foreach (['a', 'b', 'c', 'd'] as $option) {
                echo '<input type="checkbox" name="answer[]" value="' . $option . '"> ' . $option . '<br>';
            }
        } else {
            echo '<input type="text" name="answer" placeholder="Type answer here">';
        }
        ?>
        <br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>