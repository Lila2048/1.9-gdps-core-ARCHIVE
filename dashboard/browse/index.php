<?php

include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$ml = new MainLib();
$dl = new DashboardLib();

$dl->printStyle();
$dl->printHeader();

$dl->printMessageBox3("Unfinished!", "This page is still unfinished! Check back later.");

?>