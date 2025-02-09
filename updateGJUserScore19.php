<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/mainLib.php";

# Getting all the data

$udid = $_POST['udid'];
$accountID = $_POST['accountID'];
$userName = $_POST['userName'];
$stars = $_POST['stars'];
$demons = $_POST['demons'];
$icon = $_POST['icon'];
$color1 = $_POST['color1'];
$color2 = $_POST['color2'];
$iconType = $_POST['iconType'];
$coins = $_POST['coins'];
$special = $_POST['special'];
$gameVersion = $_POST['gameVersion'];
$time = time();

$ml = new mainLib();

# checking if player data is in the db already

$sql = $conn->prepare("SELECT COUNT(*) FROM users WHERE udid = :udid");
$sql->bindParam(":udid", $udid);
$sql->execute();

$result = $sql->fetchColumn();

if($result == 0) {

    # user has never submitted information before

    $sql = $conn->prepare("INSERT INTO users (udid, accountID, userName, stars, demons, color1, color2, iconType, coins, special, gameVersion, time, icon) VALUES (:udid, :accountID, :userName, :stars, :demons, :color1, :color2, :iconType, :coins, :special, :gameVersion, :time, :icon)");

} else {

    $sql = $conn->prepare("UPDATE users SET accountID = :accountID, userName = :userName, stars = :stars, demons = :demons, color1 = :color1, color2 = :color2, iconType = :iconType, coins = :coins, special = :special, gameVersion = :gameVersion, time = :time, icon = :icon WHERE udid = :udid");

}

$sql->bindParam(":udid", $udid);
$sql->bindParam(":accountID", $accountID);
$sql->bindParam(":userName", $userName);
$sql->bindParam(":stars", $stars);
$sql->bindParam(":demons", $demons);
$sql->bindParam(":color1", $color1);
$sql->bindParam(":color2", $color2);
$sql->bindParam(":iconType", $iconType);
$sql->bindParam(":coins", $coins);
$sql->bindParam(":special", $special);
$sql->bindParam(":gameVersion", $gameVersion);
$sql->bindParam(":time", $time);
$sql->bindParam(":icon", $icon);

$sql->execute();

$sql = $conn->prepare("SELECT userID FROM users WHERE udid = :udid");
$sql->bindParam(":udid", $udid);
$sql->execute();

$userID = $sql->fetchColumn();

echo($userID);

$ml->logAction(7, $udid, $accountID, $userName);

?>