<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$ml = new mainLib();
$dl = new DashboardLib();

$dl->printStyle();
$dl->printHeader();

if(!isset($_SESSION['username'], $_SESSION['password'])) {
    die($dl->printMessageBox3("Access Denied!", "You need to sign in to view this page!"));
}

$accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
$udid = $ml->getUDIDFromAccountID($accountID);
$permState = $ml->checkPerms(1, $udid);

if($permState != 1) {
    die($dl->printMessageBox3("Access Denied!", "You do not have the appropriate permissions to use this tool!"));
} else {

    if(isset($_POST['banType'], $_POST['targetID'], $_POST['expires'], $_POST['reason'])) {

        $banType = exploitPatch::clean($_POST['banType']);
        $targetID = exploitPatch::clean($_POST['targetID']);
        $expires = exploitPatch::clean($_POST['expires']);
        $reason = base64_encode(exploitPatch::clean($_POST['reason']));
        
        if($expires == 0) {
            $expires = 2147483647;
        }

        $banTypeInt = 1;

        $userInfo = $ml->getUserStats($targetID);

        if($userInfo == false) {
            die($dl->printMessageBox("Error!", "This user doesn't seem to exist..."));
        }

        # check auth

        switch($banType) {
            case "account":
                $banTypeInt = 1;
                break;
            case "uploading":
                $banTypeInt = 2;
                break;
            case "commenting":
                $banTypeInt = 3;
                break;
            case "creatorsLB":
                $banTypeInt = 4;
                break;
            case "playersLB":
                $banTypeInt = 5;
                break;
        }
            $sql = $conn->prepare("INSERT INTO bans (banType, expires, user, reason, timestamp) VALUES (:banType, :expires, :user, :reason, UNIX_TIMESTAMP())");
            $sql->execute([':banType' => $banTypeInt, ':expires' => $expires, ':user' => $targetID, ':reason' => $reason]);
        $dl->printMessageBox3("User banned!", "You successfully banned <strong>" . $userInfo['userName'] . "</strong> until <strong>" . date('Y-m-d', $expires) . "</strong>");
    } else {
        $dl->printBanUserForm();
    }
}
?>