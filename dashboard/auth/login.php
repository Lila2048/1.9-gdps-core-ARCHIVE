<?php

include __DIR__ . "/../../incl/lib/dashboardLib.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../config/main.php";

session_start();

$dl = new DashboardLib();
$ml = new MainLib();

ob_start(); // Start output buffering

$dl->printStyle();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $authState = $ml->checkAuthentication($_POST['username'], $_POST['password']);
    if ($authState != 1) {
        $_SESSION['login_error'] = 'Invalid login credentials. Please try again.';
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        header('Location: ' . $dashPath);
        exit();
    }
}

ob_end_flush(); // Flush the buffer and send output

$dl->printHeader();

if (isset($_SESSION['login_error'])) {
    $dl->printMessageBox("Invalid Credentials!", "The username and/or password entered is invalid :3");
    unset($_SESSION['login_error']);
} else {
    $dl->printLoginBox();
}
?>