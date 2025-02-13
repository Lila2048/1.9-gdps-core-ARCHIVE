<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/cron.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";

$ml = new mainLib();

session_start();

if(!isset($_SESSION['username'], $_SESSION['password'])) {
    die("<h1>Access denied!</h1>");
}

$accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
$udid = $ml->getUDIDFromAccountID($accountID);
$permState = $ml->checkPerms(1, $udid);

if($permState != 1) {
    die("<h1>Access Denied!</h1>");
} else {
        cron::refreshSongs();
        $ml->logAction(14, $_SESSION['username'], $accountID, $udid);
        die("<h1>Cron job complete!</h1>");
}
?>