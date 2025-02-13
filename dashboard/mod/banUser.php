<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";

$ml = new mainLib();

session_start();

if(!isset($_SESSION['username'], $_SESSION['password'])) {
    die("<h1>Access denied!</h1>");
}

$accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
$udid = $ml->getUDIDFromAccountID($accountID);
$permState = $ml->checkPerms(1, $udid);

if($permState != 1) {
    die("<h1>Access Denied!</h1>");
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
            displayForm();
        echo("<h1>User banned!</h1>");
    } else {
        displayForm();
    }
}

function displayForm() {
    echo("<form action='banUser.php' method='POST'>
            <label for='banType'>Ban Type:</label>
            <select id='banType' name='banType' required>
                <option value='uploading'>Uploading Levels</option>
                <option value='commenting'>Commenting</option>
                <option value='creatorsLB'>Top Creators</option>
                <option value='playersLB'>Top Players</option>
            </select>
            <br>
            <label for='targetID'>Target userID:</label>
            <input type='number' name='targetID' id='targetID' required>
            <br>
            <label for='expires'>Expires (0 for never):</label>
            <input type='number' name='expires' id='expires' required>
            <br>
            <label for='reason'>Reason:</label>
            <input type='text' name='reason' id='reason' required>
            <br>
            <input type='submit' id='submit'>
        </form>");
}

?>