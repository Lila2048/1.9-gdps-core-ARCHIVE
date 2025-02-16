<?php

session_start();

include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

$permLevel = 0;
$isAuthenticated = false;

$ml = new MainLib();
$dl = new DashboardLib();

$dl->printStyle();
$dl->printHeader();
$dl->printStatsPage();

?>