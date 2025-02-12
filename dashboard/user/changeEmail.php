<?php

if(isset($_POST['password'], $_POST['username'], $_POST['newEmail'])) {
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";
    include __DIR__ . "/../../incl/lib/exploitPatch.php";

    $ml = new MainLib();

    $username = exploitPatch::clean($_POST['username']);
    $password = exploitPatch::clean($_POST['password']);
    $newEmail = exploitPatch::clean($_POST['newEmail']);

    $authState = $ml->checkAuthentication($username, $password);

    if($authState == 1) {
        $ml->changeEmail($username, $password, $newEmail);
        $ml->logAction(13, $username, $newEmail);
        displayForm();
        echo "<h1>Email Changed!<h1>";
    } else {
        displayForm();
        die("<h1>Invalid Login Details<h1>");
    }
} else {
displayForm();
}

function displayForm() {
    echo("<form action='changeEmail.php' method='POST'>
    <label for='username'>Username:</label>
    <input type='text' name='username' id='username' required>
    <br>
    <label for='password'>Password:</label>
    <input type='password' name='password' id='password' required>
    <br>
    <label for='newEmail'>New Email:</label>
    <input type='email' name='newEmail' id='newEmail' min=3 max=20 required>
    <br>
    <input type='submit'>
</form>");
}

?>