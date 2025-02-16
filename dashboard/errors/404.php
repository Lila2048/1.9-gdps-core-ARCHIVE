<?php

include __DIR__ . "/../../incl/lib/dashboardLib.php";

$dl = new DashboardLib();

$dl->printStyle();

$dl->printMessageBox2("Error 404!", "You navigated to an invalid URL!")

?>