<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/mainLib.php";
include __DIR__ . "/config/main.php";
include __DIR__ . "/incl/lib/exploitPatch.php";

$ml = new MainLib();

# Large file spam prevention

ini_set("upload_max_filesize","50M");

# Gathering information

$udid = exploitPatch::clean($_POST['udid']);
$accountID = exploitPatch::clean($_POST['accountID']);
$userName = exploitPatch::clean($_POST['userName']);
$levelID = exploitPatch::clean($_POST['levelID']);
$levelName = exploitPatch::clean($_POST['levelName']);
$levelDesc = base64_encode(exploitPatch::clean($_POST['levelDesc']));
$levelVersion = exploitPatch::clean($_POST['levelVersion']);
$levelLength = exploitPatch::clean($_POST['levelLength']);
$audioTrack = exploitPatch::clean($_POST['audioTrack']);
$gameVersion = exploitPatch::clean($_POST['gameVersion']);
$password = exploitPatch::clean($_POST['password']);
$original = exploitPatch::clean($_POST['original']);
$twoPlayer = exploitPatch::clean($_POST['twoPlayer']);
$songID = exploitPatch::clean($_POST['songID']);
$objects = exploitPatch::clean($_POST['objects']);
$extraString = exploitPatch::clean($_POST['extraString']);
$levelString = exploitPatch::clean($_POST['levelString']);
$levelReplay = exploitPatch::clean($_POST['levelReplay']);
$secret = exploitPatch::clean($_POST['secret']);
$ip = $_SERVER['REMOTE_ADDR'];
$uploadDate = time();

# wow thats a lot

$placedID = "";

# check ban

$userID = $ml->getUserID($udid);
$banState = $ml->checkBanState($userID, 2);

if($banState == 1) {
    die("-1");
}

# rate limit check

$sql = $conn->prepare("SELECT timestamp FROM actions WHERE ip = :ip AND type = 9 ORDER BY timestamp DESC LIMIT 1");
$sql->execute(['ip' => $ip]);

$result = $sql->fetchColumn();

if($result > time() - $levelUploadTime) {
    echo("-1");
    die();
}

# resolve userID from udid

$sql = $conn->prepare("SELECT userID FROM users WHERE udid = :udid");
$sql->execute([':udid' => $udid]);

$userID = $sql->fetchColumn();

# put the level into the db (will add logic to stop empty levels etc later)
# another to do: fix the upload date thingy

if($levelID == 0) {

$sql = $conn->prepare("INSERT INTO levels (gameVersion, udid, accountID, levelName, levelDesc, levelVersion, levelLength, audioTrack, password, original, twoPlayer, songID, objects, extraString, levelReplay, uploadDate, updateDate, ip, userName, userID) VALUES (:gameVersion, :udid, :accountID, :levelName, :levelDesc, :levelVersion, :levelLength, :audioTrack, :password, :original, :twoPlayer, :songID, :objects, :extraString, :levelReplay, UNIX_TIMESTAMP(), :updateDate, :ip, :userName, :userID)");

$sql->bindParam(":gameVersion", $gameVersion);
$sql->bindParam(":udid", $udid);
$sql->bindParam(":accountID", $accountID);
$sql->bindParam(":levelName", $levelName);
$sql->bindParam(":levelDesc", $levelDesc);
$sql->bindParam(":levelVersion", $levelVersion);
$sql->bindParam(":levelLength", $levelLength);
$sql->bindParam(":audioTrack", $audioTrack);
$sql->bindParam(":password", $password);
$sql->bindParam(":original", $original);
$sql->bindParam(":twoPlayer", $twoPlayer);
$sql->bindParam(":songID", $songID);
$sql->bindParam(":objects", $objects);
$sql->bindParam(":extraString", $extraString);
$sql->bindParam(":levelReplay", $levelReplay);
$sql->bindParam(":updateDate", $uploadDate);
$sql->bindParam(":ip", $ip);
$sql->bindParam(":userName", $userName);
$sql->bindParam(":userID", $userID);

$sql->execute();

$placedID = $conn->lastInsertId();
file_put_contents(__DIR__ . "/data/levels/" . $placedID, $levelString);
echo($placedID);

} else {
    # update level

    $levelInfo = $ml->getLevelInfo($levelID);

    if($levelInfo['udid'] != $udid) {
        die("-1");
    } else {

    $sql = $conn->prepare("UPDATE levels SET gameVersion = :gameVersion, levelDesc = :levelDesc, levelVersion = :levelVersion, levelLength = :levelLength, audioTrack = :audioTrack, password = :password, original = :original, twoPlayer = :twoPlayer, songID = :songID, objects = :objects, extraString = :extraString, levelReplay = :levelReplay, updateDate = UNIX_TIMESTAMP(), ip = :ip, userName = :userName WHERE levelID = :levelID");

    $sql->bindParam(":gameVersion", $gameVersion);
    $sql->bindParam(":levelDesc", $levelDesc);
    $sql->bindParam(":levelVersion", $levelVersion);
    $sql->bindParam(":levelLength", $levelLength);
    $sql->bindParam(":audioTrack", $audioTrack);
    $sql->bindParam(":password", $password);
    $sql->bindParam(":original", $original);
    $sql->bindParam(":twoPlayer", $twoPlayer);
    $sql->bindParam(":songID", $songID);
    $sql->bindParam(":objects", $objects);
    $sql->bindParam(":extraString", $extraString);
    $sql->bindParam(":levelReplay", $levelReplay);
    $sql->bindParam(":ip", $ip);
    $sql->bindParam(":userName", $userName);
    $sql->bindParam(":levelID", $levelID);

    $sql->execute();

    file_put_contents(__DIR__ . "/data/levels/" . $levelID, $levelString);
    echo($levelID);
    }
}

$ml->logAction(9, $placedID, $udid, $userID);

?>