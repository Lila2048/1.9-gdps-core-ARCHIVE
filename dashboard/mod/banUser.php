<?php

if(isset($_POST['userName'], $_POST['password'])) {

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";

$ml = new mainLib();

$userName = $_POST['userName'];
$password = $_POST['password'];
$banType = $_POST['banType'];
$targetID = $_POST['targetID'];
$expires = $_POST['expires'];
$reason = base64_encode($_POST['reason']);

if($expires == 0) {
    $expires = 2147483647;
}

$banTypeInt = 1;

# make sure params are filled
if (empty($userName) || empty($password) || empty($banType) || empty($targetID) || empty($expires)) {
    die("<h1>All fields are required</h1>");
}

# check auth

$accountID = $ml->getAccountID($userName, $password);
$udid = $ml->getUDIDFromAccountID($accountID);
$permState = $ml->checkPerms(1, $udid);

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

if($permState != 1) {
    displayForm();
    die("<h1>Missing permissions or invalid details!</h1>");
} else {
    $sql = $conn->prepare("INSERT INTO bans (banType, expires, user, reason, timestamp) VALUES (:banType, :expires, :user, :reason, UNIX_TIMESTAMP())");
    $sql->execute([':banType' => $banTypeInt, ':expires' => $expires, ':user' => $targetID, ':reason' => $reason]);
    displayForm();
    echo("<h1>User banned!</h1>");
}

} else {
    displayForm();
}

function displayForm() {
    echo("<form action='banUser.php' method='POST'>
            <label for='userName'>Moderator Username:</label>
            <input type='text' name='userName' id='userName' required>
            <br>
            <label for='password'>Moderator Password:</label>
            <input type='password' name='password' id='password' required>
            <br>
            <label for='banType'>Ban Type:</label>
            <select id='banType' name='banType' required>
                <option value='account'>Account</option>
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