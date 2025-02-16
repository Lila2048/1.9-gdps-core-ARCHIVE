<?php

include __DIR__ . "/../../incl/lib/dashboardLib.php";

$dl = new DashboardLib();

$dl->printStyle();

$dl->printMessageBox2("Error 403!", "You don't have permission to access this resource! If you are a server admin try setting the file permissions.")

?>