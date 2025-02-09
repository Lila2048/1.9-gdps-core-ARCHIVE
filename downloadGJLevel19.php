<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/XORCipher.php";

$xor = new XORCipher();

$levelID = $_POST['levelID'];
$secret = $_POST['secret'];
$ip = $_SERVER['REMOTE_ADDR'];

if($secret != "Wmfd2893gb7") {
    die(-1);
}

# check if level has been downloaded by user

$sql = $conn->prepare("SELECT COUNT(*) FROM actions_downloads WHERE levelID = :levelID AND ip = :ip");
$sql->execute([':levelID' => $levelID, ':ip' => $ip]);

$result = $sql->fetchColumn();

if($result == 0) {

    $sql = $conn->prepare("SELECT downloads FROM levels WHERE levelID = :levelID");
    $sql->bindParam(":levelID", $levelID);
    $sql->execute();

    $result = $sql->fetchColumn();

    $downloads = $result + 1;

    $sql = $conn->prepare("UPDATE levels SET downloads = :downloads WHERE levelID = :levelID");
    $sql->bindParam(":downloads", $downloads);
    $sql->bindParam(":levelID", $levelID);
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO actions_downloads (levelID, timestamp, ip) VALUES (:levelID, UNIX_TIMESTAMP(), :ip)");
    $sql->execute([':levelID' => $levelID, ':ip' => $ip]);
}

$sql = $conn->prepare("SELECT * FROM levels WHERE levelID = :levelID");
$sql->bindParam(":levelID", $levelID);

$sql->execute();

$level = $sql->fetch(PDO::FETCH_ASSOC);

$levelData = file_get_contents(__DIR__ . "/data/levels/" . $levelID);

if($levelData === false) {
    die(-1);
}

$sql = $conn->prepare("SELECT userID FROM users WHERE accountID = :accountID");
$sql->bindParam(":accountID", $level['accountID']);
$sql->execute();
$userID = $sql->fetchColumn();

$levelString = "1:" . $level['levelID'] . ":2:" . $level['levelName'] . ":3:" . base64_decode($level['levelDesc']) . ":4:" . $levelData . ":5:" . $level['levelVersion'] . ":6:" . $userID . ":8:10" . ":9:" . $level['difficulty'] . ":10:" . $level['downloads'] . ":12:" . $level['audioTrack'] . ":13:" . $level['gameVersion'] . ":14:" . $level['likes'] . ":15:" . $level['levelLength'] . ":17:" . $level['demon'] . ":18:" . $level['stars'] . ":19:" . $level['featureScore'] . ":25:" . $level['auto'] . ":26:" . $level['levelReplay'] . ":27:1" . ":30:" . $level['copiedID'] . ":31:" . $level['twoPlayer'] . ":35:" . $level['songID'] . ":36:" . $level['extraString'];

echo($levelString);
?>