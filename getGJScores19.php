<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/mainLib.php";

$ml = new MainLib();

# getting data

$type = $_POST['type'];
$udid = $_POST['udid'];
$accountID = $_POST['accountID'];
$count = $_POST['count'];
$secret = $_POST['secret'];

$bansCsv = $ml->getCommaSeparatedBans(5);

$index = 0;
$userString = "";
$timestamp = time() - 604800;

if($secret != "Wmfd2893gb7") {
    die("-1");
}

if($type == "top") {

    $queryString = "SELECT * FROM users WHERE stars > 0 ";

    if($bansCsv != "") {
        $queryString .= "AND userID NOT IN ($bansCsv)";
    }

    # top 100

    $sql = $conn->prepare($queryString . " ORDER BY stars DESC LIMIT 100");
    $sql->execute();

    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach($result as $user) {
        $index++;
        $userString .= "1:" . $user['userName'] . ":2:" . $user['userID'] . ":3:" . $user['stars'] . ":4:" . $user['demons'] . ":6:". $index . ":7:" . $user['accountID'] . ":8:" . $user['creatorPoints'] . ":9:" . $user['icon'] . ":10:" . $user['color1'] . ":11:" . $user['color2'] . ":13:" . $user['coins'] . ":14:" . $user['iconType']. ":15:" . $user['special'] . ":16:" . $user['accountID'] . "|";
    }

    $userString = rtrim($userString, "|");

}

if($type == "relative") {

    # check if user is banned

    $userID = $ml->getUserID($udid);

    $banState = $ml->checkBanState($userID, 5);

    if($banState == 1) {
        die("-1");
    }

    # query execution

    $sql = $conn->prepare("SELECT stars FROM users WHERE udid = :udid");
    $sql->execute([':udid' => $udid]);
    $stars = $sql->fetchColumn();

    # Get the overall rank of the user
    $rankSql = $conn->prepare("SELECT COUNT(*) FROM users WHERE stars > :stars");
    $rankSql->bindParam(":stars", $stars);
    $rankSql->execute();
    $rank = $rankSql->fetchColumn();

    $sql = $conn->prepare("(SELECT * FROM users WHERE stars <= :stars ORDER BY stars DESC LIMIT 50) UNION (SELECT * FROM users WHERE stars >= :stars ORDER BY stars ASC LIMIT 50) ORDER BY stars DESC");
    $sql->bindParam(":stars", $stars);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach($result as $user) {
        $userString .= "1:" . $user['userName'] . ":2:" . $user['userID'] . ":3:" . $user['stars'] . ":4:" . $user['demons'] . ":6:". $rank . ":7:" . $user['accountID'] . ":8:" . $user['creatorPoints'] . ":9:" . $user['icon'] . ":10:" . $user['color1'] . ":11:" . $user['color2'] . ":13:" . $user['coins'] . ":14:" . $user['iconType']. ":15:" . $user['special'] . ":16:" . $user['accountID'] . "|";
        $rank++;
    }

    $userString = rtrim($userString, "|");

}

if($type == "week") {

    $userID = $ml->getUserID($udid);

    $banState = $ml->checkBanState($userID, 5);

    if($banState == 1) {
        die("-1");
    }

    $queryString = "SELECT value1, SUM(value2) AS sum_value2, SUM(value3) AS sum_value3, SUM(value4) AS sum_value4 FROM `actions` WHERE type = 7 AND timestamp > :timestamp ";

    if($bansCsv != "") {
        $queryString .= "AND userID NOT IN ($bansCsv) ";
    }

    $queryString .= "GROUP BY value1 LIMIT 100";

    $sql = $conn->prepare($queryString);
    $sql->bindParam(":timestamp", $timestamp);
    $sql->execute();

    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach($result as $user) {
        $userInfo = $ml->getUserStats($user['value1']);
        $index++;
        $userString .= "1:" . $userInfo['userName'] . ":2:" . $user['value1'] . ":3:" . $user['sum_value2'] . ":4:" . $user['sum_value3'] . ":6:". $index . ":7:" . $userInfo['accountID'] . ":8:" . $userInfo['creatorPoints'] . ":9:" . $userInfo['icon'] . ":10:" . $userInfo['color1'] . ":11:" . $userInfo['color2'] . ":13:" . $user['sum_value4'] . ":14:" . $userInfo['iconType']. ":15:" . $userInfo['special'] . ":16:" . $userInfo['accountID'] . "|";
    }

    $userString = rtrim($userString, "|");

}

echo($userString);

?>