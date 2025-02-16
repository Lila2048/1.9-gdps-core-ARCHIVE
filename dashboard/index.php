<?php

session_start();

include __DIR__ . "/../incl/lib/mainLib.php";
include __DIR__ . "/../config/dashboard.php";
include __DIR__ . "/../incl/lib/dashboardLib.php";

$ml = new MainLib();
$dl = new DashboardLib();

$dl->printStyle();

if($dashPath == "") {
    die($dl->printMessageBox2("Error!", "\$dashPath not set! Please set \$dashPath in config/dashboard.php for the dashboard to work!"));
}

$dl->printHeader();
$dl->printQuickActions();

?>