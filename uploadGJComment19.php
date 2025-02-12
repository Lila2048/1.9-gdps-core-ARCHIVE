<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/mainLib.php";
include __DIR__ . "/incl/lib/commands.php";
include __DIR__ . "/incl/lib/exploitPatch.php";

# getting data

$udid = exploitPatch::clean($_POST['udid']);
$accountID = exploitPatch::clean($_POST['accountID']);
$userName = exploitPatch::clean($_POST['userName']);
$levelID = exploitPatch::clean($_POST['levelID']);
$comment = base64_encode(exploitPatch::clean($_POST['comment']));
$commentRaw = exploitPatch::clean($_POST['comment']);
$secret = exploitPatch::clean($_POST['secret']);
$ip = $ip = $_SERVER['REMOTE_ADDR'];
$timestamp = time();

$ml = new mainLib();
$cmds = new commands();

# secret check

if($secret  != "Wmfd2893gb7") {
    die("-1");
}

# check ban status

$userID = $ml->getUserID($udid);
$isBanned = $ml->checkBanState($userID, 3);

if($isBanned == 1) {
    die("-3");
}

# check if comment is a command

$cmds->ProcessCommand($commentRaw, $udid, $levelID);

# resolve user ID

$sql = $conn->prepare("SELECT userID FROM users WHERE udid = :udid");
$sql->bindParam(":udid", $udid);
$sql->execute();

$userID = $sql->fetchColumn();

# post the comment

$sql = $conn->prepare("INSERT INTO comments (udid, accountID, userName, levelID, comment, ip, userID, timestamp) VALUES (:udid, :accountID, :userName, :levelID, :comment, :ip ,:userID, :timestamp)");
$sql->bindParam(":udid", $udid);
$sql->bindParam(":accountID", $accountID);
$sql->bindParam(":userName", $userName);
$sql->bindParam(":levelID", $levelID);
$sql->bindParam(":comment", $comment);
$sql->bindParam(":ip", $ip);
$sql->bindParam(":userID", $userID);
$sql->bindParam(":timestamp", $timestamp);

$sql->execute();

$cID = $conn->lastInsertId();

echo $cID;

$ml->logAction(8, $comment, $accountID, $udid);

?>