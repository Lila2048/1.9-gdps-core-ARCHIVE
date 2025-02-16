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

ob_start();

if($dl->checkLoginStatus() != 1) {
    ob_end_clean();
    die($dl->printMessageBox3("Access denied!", "You need to login to use this page!"));
}

if(isset($_POST['newEmail'])) {
    $newEmail = exploitPatch::clean($_POST['newEmail']);
    $authState = $ml->checkAuthentication($_SESSION['username'], $_SESSION['password']);

    if($authState == 1) {
        $result = $ml->changeEmail($_SESSION['username'], $_SESSION['password'], $newEmail);
        $ml->logAction(13, $_SESSION['username'], $newEmail);
        if($result != 1) {
            $_SESSION['message'] = ["Error!", "An error happened! Please try again later."];
        } else {
            $_SESSION['message'] = ["Success!", "Email has been changed to: " . $newEmail];
        }
    } else {
        $_SESSION['message'] = ["Access denied!", "The saved credentials are invalid! Please log in again."];
    }

    header("Location: changeEmail.php");
    exit();
}

ob_end_flush();

if(isset($_SESSION['message'])) {
    list($title, $message) = $_SESSION['message'];
    $dl->printMessageBox3($title, $message);
    unset($_SESSION['message']);
} else {
    $dl->printEmailChange();
}

?>