<?php

session_start();

if(isset($_SESSION['password'], $_SESSION['username'])) {
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";
    include __DIR__ . "/../../incl/lib/exploitPatch.php";

    if(isset($_POST['newEmail'])) {

    $ml = new MainLib();

    $newEmail = exploitPatch::clean($_POST['newEmail']);

    $authState = $ml->checkAuthentication($_SESSION['username'], $_SESSION['password']);

    if($authState == 1) {
        $result = $ml->changeEmail($_SESSION['username'], $_SESSION['password'], $newEmail);
        $ml->logAction(13, $_SESSION['username'], $newEmail);
        if($result != 1) {
            echo("<h1>Error!<h1>");
        } else {
            echo "<h1>Email Changed!<h1>";
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
    echo("<form action='changeEmail.php' method='POST'>
    <label for='newEmail'>New Email:</label>
    <input type='email' name='newEmail' id='newEmail' min=3 max=20 required>
    <br>
    <input type='submit'>
</form>");
}

?>