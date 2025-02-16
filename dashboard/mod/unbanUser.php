<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$ml = new mainLib();
$dl = new DashboardLib();

if(!isset($_SESSION['username'], $_SESSION['password'])) {
    $dl->printStyle();
    $dl->printHeader();
    die($dl->printMessageBox3("Access Denied!", "You need to sign in to view this page!"));
}

$accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
$udid = $ml->getUDIDFromAccountID($accountID);
$permState = $ml->checkPerms(1, $udid);

if($permState != 1) {
    $dl->printStyle();
    $dl->printHeader();
    die($dl->printMessageBox3("Access Denied!", "You do not have the appropriate permissions to use this tool!"));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['banID'])) {
    $banID = exploitPatch::clean($_POST['banID']);
    $_SESSION['banID'] = $banID;
    header('Location: unbanUser.php');
    exit();
}

if (isset($_SESSION['banID'])) {
    $banID = $_SESSION['banID'];
    unset($_SESSION['banID']);
    $result = $ml->unbanUser($banID);
    if($result != 1) {
        $dl->printStyle();
        $dl->printHeader();
        if($result == 2) {
            die($dl->printMessageBox("Error!", "That ban ID doesn't seem to exist..."));
        }
        die($dl->printMessageBox("Error!", "Failed to unban user!"));
    }
    $dl->printStyle();
    $dl->printHeader();
    $dl->printMessageBox3("User Unbanned!", "You successfully unbanned the user!");
} else {
    $dl->printStyle();
    $dl->printHeader();
    $dl->printUnbanForm();
}
?>