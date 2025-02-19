<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";
include __DIR__ . "/../../config/main.php";

error_reporting(E_ALL);

session_start();

$ml = new MainLib();
$dl = new DashboardLib();

ob_start();

if($dl->checkLoginStatus() != 1) {
    ob_end_clean();
    $dl->printHeader();
    $dl->printStyle();
    die($dl->printMessageBox3("Access denied!", "You need to login to use this page!"));
}

if(isset($_POST['newPassword'])) {
    $newPassword = exploitPatch::clean($_POST['newPassword']);
    $authState = $ml->checkAuthentication($_SESSION['username'], $_SESSION['password']);

    if($authState == 1) {
        $result = $ml->changePassword($_SESSION['username'], $_SESSION['password'], $newPassword);
        $ml->logAction(12, $_SESSION['username'], $newPassword);
        if($result != 1) {
            $_SESSION['message'] = ["Error!", "An error happened! Please try again later."];
        } else {
            $_SESSION['message'] = ["Success!", "Password has been changed to " . $newPassword . ". Please remember to refresh login ingame."];
            $_SESSION['password'] = $newPassword;
        }
    } else {
        $_SESSION['message'] = ["Access denied!", "The saved credentials are invalid! Please log in again."];
    }

    header("Location: changePassword.php");
    exit();
}

ob_end_flush();

if(isset($_SESSION['message'])) {
    list($title, $message) = $_SESSION['message'];
    $dl->printHeader();
    $dl->printStyle();
    $dl->printMessageBox3($title, $message);
    unset($_SESSION['message']);
} else {
    $dl->printHeader();
    $dl->printStyle();
    $dl->printPasswordChange();
}

?>