<?php
session_start();

$timeout_duration = 300; //have to be logged in to access, set this to make easier for TA's to test

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$mysqli = new mysqli(
    "spring-2024.cs.utexas.edu",
    "cs329e_bulko_csofia",
    "among4tax5Mutton",
    "cs329e_bulko_csofia"
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$is_ajax_request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['name']) && !empty($_POST['item'])) {
    $name = $mysqli->real_escape_string($_POST['name']);
    $item = $mysqli->real_escape_string($_POST['item']);
    $sql = "INSERT INTO dinner (name, item) VALUES ('$name', '$item')";

    if ($mysqli->query($sql) === true) {
        if ($is_ajax_request) {
            echo json_encode(['success' => true, 'name' => $name, 'item' => $item, 'message' => "Item added successfully!"]);
            exit;
        } else {
            $_SESSION['message'] = "Item added successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        $message = "Error: " . $mysqli->error;
        if ($is_ajax_request) {
            echo json_encode(['success' => false, 'message' => $message]);
            exit;
        }
    }
}

if (!$is_ajax_request && isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

$result = $mysqli->query("SELECT name, item FROM dinner");

?>

<!DOCTYPE html>
<html>

<head>
    <title>Potluck Signup</title>
    <link rel="stylesheet" type="text/css" href="potluck.css">

</head>

<body>
    <h2 class="special-title">Potluck Signup</h2>
    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>
    <form id="signupForm" method="post">
        NAME: <input type="text" name="name" maxlength="20"><br>
        ITEM: <input type="text" name="item" maxlength="50"><br>
        <input type="submit" id="submitBtn" value="Submit">
    </form>

    <h3>Current List</h3>
    <table>
        <tr>
            <th>NAME</th>
            <th>ITEM</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['item']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var form = document.getElementById('signupForm');
            var submitBtn = document.getElementById('submitBtn');
            var messageBox = document.getElementById('messageBox');

            submitBtn.addEventListener('click', function (e) {
                e.preventDefault(); /

                var xhr = new XMLHttpRequest();
                var formData = new FormData(form);

                xhr.open('POST', 'signup.php', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.onload = function () {
                    if (this.status == 200) {
                        var result = JSON.parse(this.response);

                        if (result.success) {
                            var table = document.querySelector('table');
                            var newRow = table.insertRow(-1);
                            var nameCell = newRow.insertCell(0);
                            var itemCell = newRow.insertCell(1);

                            nameCell.textContent = result.name;
                            itemCell.textContent = result.item;

                            form.reset();

                            messageBox.textContent = result.message;
                            messageBox.className = 'success';

                            setTimeout(function () {
                                messageBox.textContent = '';
                                messageBox.className = '';
                            }, 4000);
                        } else {
                            messageBox.textContent = result.message;
                            messageBox.className = 'error';
                        }
                    } else {
                        alert('An error occurred while processing your request.');
                    }
                };

                xhr.send(formData);
            });
        });
    </script>

</body>

</html>