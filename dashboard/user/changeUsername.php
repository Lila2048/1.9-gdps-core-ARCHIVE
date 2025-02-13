<?php

session_start();

if(isset($_SESSION['password'], $_SESSION['username'])) {
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";
    include __DIR__ . "/../../incl/lib/exploitPatch.php";

    if(isset($_POST['newUsername'])) {

    $ml = new MainLib();

    $newUsername = exploitPatch::clean($_POST['newUsername']);

    $authState = $ml->checkAuthentication($_SESSION['username'], $_SESSION['password']);

    if($authState == 1) {
        $result = $ml->changeUsername($_SESSION['username'], $_SESSION['password'], $newUsername);
        $ml->logAction(11, $_SESSION['username'], $newUsername);
        if($result != 1) {
            echo("<h1>Error!<h1>");
        } else {
            echo "<h1>Username Changed! Make sure to refresh login ingame!<h1>";
        }
    } else {
        die("<h1>Access Denied!<h1>");
    }
} else {
    displayForm();
}
} else {
    die("<h1>Access denied!</h1>");
}

function displayForm() {
    echo("<form action='changeUsername.php' method='POST'>
    <label for='newUsername'>New Username:</label>
    <input type='text' name='newUsername' id='newUsername' min=3 max=20 required>
    <br>
    <input type='submit'>
</form>");
}

?>