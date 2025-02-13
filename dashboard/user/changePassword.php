<?php

session_start();

if(isset($_SESSION['password'], $_SESSION['username'])) {
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";
    include __DIR__ . "/../../incl/lib/exploitPatch.php";

    if(isset($_POST['newPassword'])) {

    $ml = new MainLib();

    $newPassword = exploitPatch::clean($_POST['newPassword']);

    $authState = $ml->checkAuthentication($_SESSION['username'], $_SESSION['password']);

    if($authState == 1) {
        $result = $ml->changePassword($_SESSION['username'], $_SESSION['password'], $newPassword);
        $ml->logAction(12, $_SESSION['username']);
        if($result != 1) {
            echo("<h1>Error!<h1>");
        } else {
            echo "<h1>Password Changed! Make sure to refresh login ingame!<h1>";
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
    echo("<form action='changePassword.php' method='POST'>
    <label for='newPassword'>New Password:</label>
    <input type='text' name='newPassword' id='newPassword' min=6 max=20 required>
    <br>
    <input type='submit'>
</form>");
}

?>