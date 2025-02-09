<?php

if(isset($_POST['username'], $_POST['password'])) {
    # check auth
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";

    $ml = new MainLib();

    $userName = $_POST['username'];
    $password = $_POST['password'];
    $stars = $_POST['stars'];
    $levelID = $_POST['levelID'];
    $feature = $_POST['feature'];

    $accountID = $ml->getAccountID($userName, $password);
    $udid = $ml->getUDIDFromAccountID($accountID);
    $permState = $ml->checkPerms(1, $udid);

    if($permState != 1) {
        displayForm();
        die("><h1>Missing perms or invalid login!</h1>");
    } else {
        $ml->sendLevel($levelID, $stars, $feature, $udid);
        displayForm();
        echo("><h1>Level Sent!<h1>");
    }
} else {
    # display auth form
    displayForm();
}

function displayForm() {
    echo "<form action='sendLevel.php' method='POST'>
    <label for='username'>Username:</label>
    <input type='text' name='username' id='username' required>
    <br>
    <label for='password'>Password:</label>
    <input type='password' name='password' id='password' required>
    <br>
    <label for='levelID'>levelID:</label>
    <input type='number' name='levelID' id='levelID' min=0 required>
    <br>
    <label for='stars'>Stars:</label>
    <input type='number' name='stars' id='stars' min=0 max=10 required>
    <br>
    <label for='feature'>Featured (1 for feature 0 for rate)</label>
    <input type='number' name='feature' id='feature' min=0 max=1 required>
    <br>
    <input type='submit'>
    </form>";
}

?>