<?php

if(isset($_POST['password'], $_POST['username'], $_POST['newUsername'])) {
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";

    $ml = new MainLib();

    $username = exploitPatch::clean($_POST['username']);
    $password = exploitPatch::clean($_POST['password']);
    $newUsername = exploitPatch::clean($_POST['newUsername']);

    $authState = $ml->checkAuthentication($username, $password);

    if($authState == 1) {
        $ml->changeUsername($username, $password, $newUsername);
        $ml->logAction(11, $username, $newUsername);
        displayForm();
        echo "<h1>Username changed! Remember to refresh login ingame!<h1>";
    } else {
        displayForm();
        echo("<h1>Invalid Login Details!</h1>");
    }
} else {
displayForm();
}

function displayForm() {
    echo("<form action='changeUsername.php' method='POST'>
    <label for='username'>Username:</label>
    <input type='text' name='username' id='username' required>
    <br>
    <label for='password'>Password:</label>
    <input type='password' name='password' id='password' required>
    <br>
    <label for='newUsername'>New Username:</label>
    <input type='text' name='newUsername' id='newUsername' min=3 max=20 required>
    <br>
    <input type='submit'>
</form>");
}

?>