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
    if(isset($_POST['username'], $_POST['newPassword'])) {
        $state = $ml->forceChangePassword($_POST['username'], $_POST['newPassword']);
        if($state == 0) {
            header("Location: forceChangePassword.php?status=0&username=" . urlencode($_POST['username']));
            exit();
        }
        if($_POST['username'] == $_SESSION['username']) {
            $_SESSION['password'] = $_POST['newPassword'];
        }
        header("Location: forceChangePassword.php?status=1&username=" . urlencode($_POST['username']));
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $dl = new DashboardLib();
    if(isset($_GET['status'], $_GET['username'])) {
        if($_GET['status'] == 1) {
            $dl->printStyle();
            $dl->printHeader();
            $dl->printMessageBox3("Password Changed!", "You changed " . htmlspecialchars($_GET['username']) . "'s password!");
        } else {
            $dl->printStyle();
            $dl->printHeader();
            $dl->printMessageBox("Failed to change password!", "The password of the account ".$_GET['username']." failed to change. This is probably because the account does not exist.");
        }
    } else {
        $dl->printStyle();
        $dl->printHeader();
        $dl->printForceChangePassword();
    }
}

?>