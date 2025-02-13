<?php

session_start();

if(isset($_SESSION['username'], $_SESSION['username'])) {
    # check auth
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";
    include __DIR__ . "/../../incl/lib/exploitPatch.php";

    $ml = new MainLib();

    $accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
    $udid = $ml->getUDIDFromAccountID($accountID);
    $permState = $ml->checkPerms(1, $udid);

    if($permState != 1) {
        die("<h1>Access denied!</h1>");
    }

    if(isset($_POST['stars'], $_POST['levelID'], $_POST['feature'])) {

        $stars = exploitPatch::clean($_POST['stars']);
        $levelID = exploitPatch::clean($_POST['levelID']);
        $feature = exploitPatch::clean($_POST['feature']);

        $ml->sendLevel($levelID, $stars, $feature, $udid);
        displayForm();
        echo("<h1>Level Sent!<h1>");

    } else {
        displayForm();
    }

} else {
    die("<h1>Access denied!</h1>");
}

function displayForm() {
    echo "<form action='sendLevel.php' method='POST'>
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