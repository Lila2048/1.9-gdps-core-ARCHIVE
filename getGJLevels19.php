<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/mainLib.php";

$ml = new MainLib();

# gathering data

$type = $_POST['type'];
$str = $_POST['str'];
$diff = $_POST['diff'];
$len = $_POST['len'];
$page = $_POST['page'];
$total = $_POST['total'];
$uncompleted = $_POST['uncompleted'];
$featured = $_POST['featured'];
$original = $_POST['original'];
$twoPlayer = $_POST['twoPlayer'];
$gameVersion = $_POST['gameVersion'];
$secret = $_POST['secret'];

# setting misc params

$completedLevels = "";
$noStar = "";
$star = "";
$song = -1;

# optional params

if(isset($_POST['completedLevels'])) {
    $completedLevels = $_POST['completedLevels'];
}

if(isset($_POST['noStar'])) {
    $noStar = $_POST['noStar'];
}

if(isset($_POST['star'])) {
    $star = $_POST['star'];
}

if(isset($_POST['song'])) {
    $song = $_POST['song'];
}

if(isset($_POST['customSong'])) {
    $isCustomSong = $_POST['customSong'];
}

$trendingTime = time() - 604800;

$page2 = $page * 10;
$index = $page2 + 1;

# Secret check

if($secret != "Wmfd2893gb7") {
    die("-1");
}

$queryStringStart = "SELECT * FROM levels "; # var for the query builder
$levelString = ""; # level string to return to the client
$songString = "";
$creatorsString = "";
$extra = 0; # logic for level count query, used by types that filter
$queryString2 = "LIMIT 10 OFFSET $page2";
$orderString = "";
$queryString = "";

# Actual code

# types

switch($type) {
    case 0:
        # name search
        if(!is_numeric($str)) {
            $str = "%$str%";
            $queryString .= "WHERE levelName LIKE $str ";
        } else {
            $queryString .= "WHERE levelID = $str ";
        }
        $orderString .= "ORDER BY likes DESC ";
        break;
    case 1: # most downloaded
        $queryString .= "WHERE 1 ";
        $orderString .= "ORDER BY downloads DESC ";
        break;
    case 2: # most liked
        $queryString .= "WHERE 1 ";
        $orderString .= "ORDER BY likes DESC ";
        break;
    case 3: # trending
        $queryString .= "WHERE uploadDate > $trendingTime ";
        $orderString .= "ORDER BY likes DESC ";
        $extra = 3;
        break;
    case 4: # recent tab (oh no)
        $queryString .= "WHERE 1 ";
        $orderString .= "ORDER BY uploadDate DESC ";
        break;
    case 5: # levels by user
        $extra = 5;
        $queryString .= "WHERE userID = $str ";
        $orderString .= "ORDER BY uploadDate DESC ";
        break;
    case 6: # featured
        $extra = 6;
        $queryString .= "WHERE featureScore >= 1 ";
        $orderString .= "ORDER BY rateDate DESC ";
        break;
    case 7: # magic tab
        $extra = 7;
        $queryString .= "WHERE objects > 9999 AND stars = 0 ";
        $orderString .= "ORDER BY uploadDate DESC ";
        break;
    case 10: # map packs
        $extra = 10;
        $queryString .= "WHERE levelID IN ($str) ";
        $orderString .= "ORDER BY stars DESC ";
        break;
}

# diffs

switch($diff) {
    case -1:
        $queryString .= "AND difficulty = 0 ";
        break;
    case -3:
        $queryString .= "AND auto = 1 ";
        break;
    case -2:
        $queryString .= "AND demon = 1 ";
        break;
    case "-":
        break;
    default:
        $diff = str_replace(",", "0,", $diff) . "0";
        $queryString .= "AND difficulty IN ($diff) AND auto = 0 AND demon = 0 ";
        break;
}

# length

if ($len != "-") {
    $queryString .= "AND levelLength IN ($len) ";
}

# misc types

if($original == 1) {
    $queryString .= "AND original = 0 ";
}

if($uncompleted == "") {
    $queryString .= "AND levelID NOT IN ($completedLevels) ";
}

if($featured == 1) {
    $queryString .= "AND featureScore > 0 ";
}

if($twoPlayer == 1) {
    $queryString .= "AND twoPlayer = 1 ";
}

if($star == 1) {
    $queryString .= "AND stars > 0 ";
}

if($noStar == 1) {
    $queryString .= "AND stars = 0 ";
}

if($song !=  -1) {
    if(isset($isCustomSong)) {
        $queryString .= "AND songID = $song ";
    } else {
        # apparently rob made songs start from 0 in the db but stereo madness is id 1 according to this endpoint
        $song = $song - 1;
        $queryString .= "AND audioTrack = $song AND songID = 0 ";
    }
}

$sql = $conn->prepare($queryStringStart . $queryString . $orderString . $queryString2);

$sql->execute();

$result = $sql->fetchAll(PDO::FETCH_ASSOC);

# levels string

foreach($result as $level) {
    $sql = $conn->prepare("SELECT userID FROM users WHERE accountID = :accountID");
    $sql->bindParam(':accountID', $level['accountID']);
    $sql->execute();
    $userID = $sql->fetchColumn();

    $levelString .= "1:" . $level['levelID'] . ":2:" . $level['levelName'] . ":3:" . base64_decode($level['levelDesc']) . ":5:" . $level['levelVersion'] . ":6:" . $userID . ":8:10" . ":9:" . $level['difficulty'] . ":10:" . $level['downloads'] . ":12:" . $level['audioTrack'] . ":13:" . $level['gameVersion'] . ":14:" . $level['likes'] . ":15:" . $level['levelLength'] . ":17:" . $level['demon'] . ":18:" . $level['stars'] . ":19:" . $level['featureScore'] . ":25:" . $level['auto'] . ":26:" . $level['levelReplay'] . ":30:" . $level['copiedID'] . ":31:" . $level['twoPlayer'] . ":35:" . $level['songID'] . ":36:" . $level['extraString']. "|";

    if($level['songID'] != 0) {
        $songString .= $ml->getSongInfo($level['songID']) . "~:~";
    }
}

$songString = rtrim($songString, ":");
$levelString = rtrim($levelString, "|");

# creators string

foreach($result as $level) {
    $sql = $conn->prepare("SELECT userID FROM users WHERE accountID = :accountID");
    $sql->bindParam(':accountID', $level['accountID']);
    $sql->execute();
    $userID = $sql->fetchColumn();
    $creatorsString .= $userID . ":" . $level['userName'] . ":" . $level['accountID'] . "|";
}

$creatorsString = rtrim($creatorsString, "|");

echo($levelString . "#" . $creatorsString . "#" . $songString);

# levels count

$query = $conn->prepare("SELECT count(*) FROM levels " . $queryString . $orderString);
$query->execute();
$levelCount = $query->fetchColumn();

echo("#" . $levelCount . ":" . $page2 . ":10");

?>