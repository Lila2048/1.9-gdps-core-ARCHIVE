<?php

session_start();

include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

$ml = new MainLib();
$dl = new DashboardLib();

$dl->printStyle();
$dl->printHeader();

if($dl->checkPermsLevel() < 0) {
    die($dl->printMessageBox3("Access Denied!", "You are either not logged in or do not have the appropriate permission to access this tool."));
}

$dl->printModActions();

?>