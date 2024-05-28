<?php
session_start();

$username = 'potluck';
$password = 'feedMeN0w';

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        $_SESSION['logged_in'] = true;
        header("Location: signup.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}

include 'login_form.html';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="potluck.css">
</head>

<body>
    <h2 class="special-title">Login</h2>
    <form action="login.php" method="post">
        USERNAME: <input type="text" name="username"><br>
        PASSWORD: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>

</html>