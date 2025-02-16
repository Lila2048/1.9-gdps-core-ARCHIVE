<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";
include __DIR__ . "/../../config/main.php";

error_reporting(E_ALL); // Enable error reporting for debugging

session_start();

$ml = new MainLib();
$dl = new DashboardLib();

$dl->printHeader();
$dl->printStyle();

ob_start(); // Start output buffering

if($dl->checkLoginStatus() != 1) {
    ob_end_clean(); // Clear the buffer
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
            // Clear session data to log out the user
            session_unset();
            session_destroy();
        }
    } else {
        $_SESSION['message'] = ["Access denied!", "The saved credentials are invalid! Please log in again."];
    }

    // Redirect to avoid form resubmission
    header("Location: changePassword.php");
    exit();
}

ob_end_flush(); // Flush the buffer and send output

if(isset($_SESSION['message'])) {
    list($title, $message) = $_SESSION['message'];
    $dl->printMessageBox4($title, $message, $dbPath . "/auth/logout.php", "Home");
    unset($_SESSION['message']);
} else {
    $dl->printPasswordChange();
}

?>