<?php

if(isset($_POST['username'], $_POST['password'])) {
    # check auth
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";
    include __DIR__ . "/../../incl/lib/cron.php";

    $ml = new MainLib();

    $userName = $_POST['username'];
    $password = $_POST['password'];

    $accountID = $ml->getAccountID($userName, $password);
    $udid = $ml->getUDIDFromAccountID($accountID);
    $permState = $ml->checkPerms(2, $udid);

    if($permState != 1) {
        displayForm();
        die("<h1>Missing perms or invalid login!</h1>");
    } else {
        cron::refreshSongs();
        $ml->logAction(14, $userName, $accountID, $udid);
        displayForm();
        die("<h1>Cron job complete!</h1>");
    }
} else {
    # display auth form
    displayForm();
}

function displayForm() {
    echo "<form action='runCron.php' method='POST'>
    <label for='username'>Username:</label>
    <input type='text' name='username' id='username' required>
    <br>
    <label for='password'>Password:</label>
    <input type='password' name='password' id='password' required>
    <br>
    <input type='submit'>
    </form>";
}

?>