<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";
include __DIR__ . "/../../config/main.php";

error_reporting(0);

session_start();

$ml = new MainLib();
$dl = new DashboardLib();

$dl->printHeader();
$dl->printStyle();

ob_start();

if($dl->checkLoginStatus() != 1) {
    ob_end_clean();
    $dl->printHeader();
    $dl->printStyle();
    die($dl->printMessageBox3("Access denied!", "You need to login to use this page!"));
}

if(isset($_POST['newUsername'])) {
    $newUsername = exploitPatch::clean($_POST['newUsername']);
    $authState = $ml->checkAuthentication($_SESSION['username'], $_SESSION['password']);

    if($authState == 1) {
        $result = $ml->changeUsername($_SESSION['username'], $_SESSION['password'], $newUsername);
        $ml->logAction(11, $_SESSION['username'], $newUsername);
        if($result != 1) {
            $_SESSION['message'] = ["Error!", "An error happened! Please try again later."];
        } else {
            $_SESSION['message'] = ["Success!", "Username has been changed to " . $newUsername . ". Please remember to refresh login ingame."];
            $_SESSION['username'] = $newUsername;
        }
    } else {
        $_SESSION['message'] = ["Access denied!", "The saved credentials are invalid! Please log in again."];
    }

    header("Location: changeUsername.php");
    exit();
}

ob_end_flush();

if(isset($_SESSION['message'])) {
    list($title, $message) = $_SESSION['message'];
    $dl->printHeader();
    $dl->printStyle();
    $dl->printMessageBox4($title, $message, $dbPath . "/auth/logout.php", "Home");
    unset($_SESSION['message']);
} else {
    $dl->printUsernameChange();
}

?>