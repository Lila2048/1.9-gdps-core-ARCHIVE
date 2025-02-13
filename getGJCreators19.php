<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/mainLib.php";

$ml = new MainLib();

$bansCsv = $ml->getCommaSeparatedBans(4);

# getting data

$type = $_POST['type'];
$udid = $_POST['udid'];
$accountID = $_POST['accountID'];
$count = $_POST['count'];
$secret = $_POST['secret'];

$userString = "";
$index = 0;

if($secret != "Wmfd2893gb7") {
    die("-1");
}

if($type == "top") {

    $queryString = "SELECT * FROM users WHERE creatorPoints > 0 AND userID";

    if($bansCsv != "") {
        $queryString .= " NOT IN ($bansCsv)";
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

    echo($userString);

}

?>