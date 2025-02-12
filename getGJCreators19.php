<?php

include __DIR__ . "/incl/lib/connection.php";

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

    # top 100

    $sql = $conn->prepare("SELECT * FROM users WHERE creatorPoints > 0 ORDER BY creatorPoints DESC LIMIT 100");
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