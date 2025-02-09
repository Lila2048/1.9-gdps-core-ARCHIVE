<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/mainLib.php";
include __DIR__ . "/config/main.php";

$ml = new MainLib();

# Large file spam prevention

ini_set("upload_max_filesize","50M");

# Gathering information

$udid = $_POST['udid'];
$accountID = $_POST['accountID'];
$userName = $_POST['userName'];
$levelID = $_POST['levelID'];
$levelName = $_POST['levelName'];
$levelDesc = base64_encode($_POST['levelDesc']);
$levelVersion = $_POST['levelVersion'];
$levelLength = $_POST['levelLength'];
$audioTrack = $_POST['levelLength'];
$gameVersion = $_POST['gameVersion'];
$password = $_POST['password'];
$original = $_POST['original'];
$twoPlayer = $_POST['twoPlayer'];
$songID = $_POST['songID'];
$objects = $_POST['objects'];
$extraString = $_POST['extraString'];
$levelString = $_POST['levelString'];
$levelReplay = $_POST['levelReplay']; # :p
$secret = $_POST['secret'];
$ip = $_SERVER['REMOTE_ADDR'];
$uploadDate = time();

# wow thats a lot

# rate limit check

$sql = $conn->prepare("SELECT timestamp FROM actions WHERE ip = :ip AND type = 9 ORDER BY timestamp DESC LIMIT 1");
$sql->execute(['ip' => $ip]);

$result = $sql->fetchColumn();

if($result > time() - $levelUploadTime) {
    echo(-1);
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

} else {
    die(-1);
}

if($levelID == 0) {
    # new level
    $placedID = $conn->lastInsertId();
    file_put_contents(__DIR__ . "/data/levels/" . $placedID, $levelString);
    echo($placedID);
} else {
    # updating level
    file_put_contents(__DIR__ . "/data/levels/" . $levelID, $levelString);
    echo($levelID);
}

$ml->logAction(9, $placedID, $udid, $userID);

?>