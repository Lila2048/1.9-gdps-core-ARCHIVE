<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$ml = new mainLib();

if(!isset($_SESSION['username'], $_SESSION['password'])) {
    $dl = new DashboardLib();
    $dl->printStyle();
    $dl->printHeader();
    die($dl->printMessageBox3("Access Denied!", "You need to sign in to view this page!"));
}

$accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
$udid = $ml->getUDIDFromAccountID($accountID);
$permState = $ml->checkPerms(2, $udid);

if($permState != 1) {
    $dl = new DashboardLib();
    $dl->printStyle();
    $dl->printHeader();
    die($dl->printMessageBox3("Access Denied!", "You do not have the appropriate permissions to use this tool!"));
} else {
    if(isset($_POST['levelID'], $_POST['stars'], $_POST['featuredLevel'])) {
        if($ml->doesLevelExist($_POST['levelID']) != 1) {
            $dl = new DashboardLib();
            $dl->printStyle();
            $dl->printHeader();
            die($dl->printMessageBox("Error!", "That level doesn't exist!"));
        }
        $dl = new DashboardLib();
        $levelInfo = $ml->getLevelInfo($_POST['levelID']);
        $dl->printStyle();
        $dl->printHeader();
        $dl->printMessageBox3("Level Rated!", "You successfully rated " . $levelInfo['levelName'] . " " . $_POST['stars'] . " stars");
        $ml->rateLevel($_POST['levelID'], $_POST['stars'], $_POST['featuredLevel']);
    } else {
        $dl = new DashboardLib();
        $dl->printStyle();
        $dl->printHeader();
        $dl->printRateForm();
    }
}

?>