<?php
session_start();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // chheck if user credentials are correct
        if (file_exists("passwd.txt")) {
            $lines = file('passwd.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $authenticated = false;
            foreach ($lines as $line) {
                list($fileUser, $filePass) = explode(':', $line);
                if ($username == $fileUser && $password == $filePass) {
                    $authenticated = true;
                    break;
                }
            }
            if ($authenticated) {
                // taken quiz alr
                if (file_exists("results.txt")) {
                    $results = file('results.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($results as $result) {
                        list($resultUser, $score) = explode(':', $result);
                        if ($username == $resultUser) {
                            $error = "You have already taken the quiz.";
                            $authenticated = false; // no login if alr taken
                            break;
                        }
                    }
                }
                if ($authenticated) {
                    $_SESSION['username'] = $username;
                    $_SESSION['start_time'] = time();
                    $_SESSION['expire_time'] = $_SESSION['start_time'] + (15 * 60);
                    header("Location: quiz.php");
                    exit;
                }
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Error: The user file does not exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="quiz.css">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <form method="POST">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    <?php if (!empty($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
</body>

</html>