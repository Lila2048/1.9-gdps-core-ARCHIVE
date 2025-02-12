<?php

if(isset($_POST['password'], $_POST['username'], $_POST['newPassword'])) {
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";
    include __DIR__ . "/../../incl/lib/exploitPatch.php";

    $ml = new MainLib();

    $username = exploitPatch::clean($_POST['username']);
    $password = exploitPatch::clean($_POST['password']);
    $newPassword = exploitPatch::clean($_POST['newPassword']);

    $authState = $ml->checkAuthentication($username, $password);

    if($authState == 1) {
        $ml->changePassword($username, $password, $newPassword);
        $ml->logAction(12, $username);
        displayForm();
        echo "<h1>Password changed! Remember to refresh login ingame!<h1>";
    } else {
        displayForm();
        die("<h1>Invalid Login Details<h1>");
    }
} else {
    displayForm();
}

function displayForm() {
    echo("<form action='changePassword.php' method='POST'>
    <label for='username'>Username:</label>
    <input type='text' name='username' id='username' required>
    <br>
    <label for='password'>Password:</label>
    <input type='password' name='password' id='password' required>
    <br>
    <label for='newPassword'>New Password:</label>
    <input type='text' name='newPassword' id='newPassword' min=6 max=20 required>
    <br>
    <input type='submit'><form>");
}

?>