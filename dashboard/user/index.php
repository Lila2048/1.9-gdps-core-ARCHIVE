<?php

session_start();

include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

$ml = new MainLib();
$dl = new DashboardLib();

$dl->printStyle();
$dl->printHeader();

if($dl->checkLoginStatus() != 1) {
    die($dl->printMessageBox3("Access Denied", "This page requires an account to view!"));
}

$dl->printUserActions();

?>