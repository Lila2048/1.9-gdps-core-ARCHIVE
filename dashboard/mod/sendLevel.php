<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$ml = new MainLib();
$dl = new DashboardLib();

$dl->printStyle();
$dl->printHeader();

if(!isset($_SESSION['username'], $_SESSION['username'])) {
    die($dl->printMessageBox3("Access denied!", "Please log in to use this page!"));
}
    # check auth

    $accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
    $udid = $ml->getUDIDFromAccountID($accountID);
    $permState = $ml->checkPerms(1, $udid);

    if($permState != 1) {
        die($dl->printMessageBox3("Access denied!", "You do not have the correct permissions to use this page!"));
    }

    if(isset($_POST['stars'], $_POST['levelID'], $_POST['feature'])) {

        $stars = exploitPatch::clean($_POST['stars']);
        $levelID = exploitPatch::clean($_POST['levelID']);
        $feature = exploitPatch::clean($_POST['feature']);

        if($stars > 10 || $stars < 1 || $feature > 1 || $feature < 0) {
            die($dl->printMessageBox("Error!", "The data you entered is invalid!"));
        }

        $levelInfo = $ml->getLevelInfo($levelID);

        if($levelInfo == false) {
            die($dl->printMessageBox("Error!", "This level doesn't seem to exist..."));
        }

        $ml->sendLevel($levelID, $stars, $feature, $udid);
        die($dl->printMessageBox5("Level sent!", "You successfully sent <strong>" . $levelInfo['levelName'] . "</strong> For $stars stars"));

    } else {
        $dl->printSendForm();
    }

?>