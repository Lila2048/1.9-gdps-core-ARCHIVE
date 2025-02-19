<?php

include __DIR__ . "/incl/lib/mainLib.php";
include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/config/main.php";
include __DIR__ . "/config/main.php";

# gathering data

$secret = $_POST['secret'];
$udid = $_POST['udid'];
$levelID = $_POST['levelID'];
$accountID =  $_POST['accountID'];

if($accountID == 0 && $requireAuthentication == true) {
    die("-1");
}

$ml = new MainLib();

if($ratedLevelDeletes == false) {
    $levelInfo = $ml->getLevelInfo($levelID);
    if($levelInfo['stars'] != 0) {
        die("-1");
    }
} else {
    $levelInfo = $ml->getLevelInfo($levelID);
    if($levelInfo['stars'] != 0) {
    $ml->unrateLevel($levelID);
    }
}

# secret check

if($secret != "Wmfv2898gc9") {
    die("-1");
}

$sql = $conn->prepare("SELECT COUNT(*) FROM levels WHERE udid = :udid AND levelID = :levelID");
$sql->execute([':udid' => $udid, ':levelID' => $levelID]);

$result = $sql->fetchColumn();

if($result == 1) {
    $ml->deleteLevel($levelID);
    echo(1);
} else {
    echo(-1);
    die();
}
?>