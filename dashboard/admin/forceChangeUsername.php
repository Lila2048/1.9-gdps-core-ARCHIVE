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
$permState = $ml->checkPerms(2, $udid);

if($permState != 1) {
    $dl->printStyle();
    $dl->printHeader();
    die($dl->printMessageBox3("Access Denied!", "You do not have the appropriate permissions to use this tool!"));
} else {
    if(isset($_POST['oldUsername'], $_POST['newUsername'])) {
        $status = $ml->forceChangeUsername($_POST['oldUsername'], $_POST['newUsername']);
        header("Location: forceChangeUsername.php?status=".$status."&oldUsername=" . urlencode($_POST['oldUsername']) . "&newUsername=" . urlencode($_POST['newUsername']));
        exit();
    }
}

if (isset($_GET['status'])) {
    $dl->printStyle();
    $dl->printHeader();
    if($_GET['status'] == 1) {
        if($_SESSION['username'] == $_GET['oldUsername']) {
        $_SESSION['username'] == $_GET['newUsername'];
        }
        $dl->printMessageBox3("Username changed!", "You changed " . htmlspecialchars($_GET['oldUsername']) . " to " . htmlspecialchars($_GET['newUsername']));
    } else {
        $dl->printMessageBox("Error!", "This account either doesn't exist or the new username is already taken!");
    }
} else {
    $dl->printStyle();
    $dl->printHeader();
    $dl->printForceChangeUsername();
}

?>