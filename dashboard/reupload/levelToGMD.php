<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../config/dashboard.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$ml = new MainLib();

if(isset($_POST['levelID'])) {
    # check level ownership
    $accID = $ml->getAccountID($_SESSION['username']);
    $udid = $ml->getUDIDFromAccountID($accID);
    $userID = $ml->getUserID($udid);
    if($ml->doesLevelExist($_POST['levelID']) == 1) {
        $levelInfo = $ml->getLevelInfo($_POST['levelID']);
        if($levelInfo['udid'] != $udid) {
            $dl = new DashboardLib();
            $dl->printStyle();
            $dl->printHeader();
            die($dl->printMessageBox("Error!", "You don't own that level..."));
        } else {
            $levelString = file_get_contents(__DIR__ . "/../../data/levels/" . $_POST['levelID']);
            $gmdFile = "<d><k>kCEK</k><i>4</i><k>k2</k><s>".$levelInfo['levelName']."</s><k>k3</k><s>".$levelInfo['levelDesc']."</s><k>k4</k><s>".$levelString."</s></d>";
            # download

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $levelInfo['levelName'] . '.gmd');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($gmdFile));
            header("Content-Type: text/plain");
            echo($gmdFile);
        }
    } else {
        $dl = new DashboardLib();
        $dl->printStyle();
        $dl->printHeader();
        die($dl->printMessageBox("Error!", "This level doesn't seem to exist..."));
    }

} else  {
    $dl = new DashboardLib();
    $dl->printStyle();
    $dl->printHeader();
    if($dl->checkLoginStatus() != 1) {
        die($dl->printMessageBox3("Error!", "Please login to use this page!"));
    } else {
        die($dl->printLevelToGMD());
    }
}

?>