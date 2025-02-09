<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/DiscordWebhook.php";
include __DIR__ . "/config/webhooks.php";

# getting data

$levelID = $_POST['levelID'];
$secret = $_POST['secret'];
$ip = $ip = $_SERVER['REMOTE_ADDR'];

$dw = new DiscordWebhook($levelReportWebhook);

if($secret != "Wmfd2893gb7") {
    die(-1);
}

# anti spam measure as no credentials are passed in

$sql = $conn->prepare("SELECT COUNT(*) FROM reports WHERE levelID = :levelID AND ip = :ip");
$sql->bindParam(":levelID", $levelID);
$sql->bindParam(":ip", $ip);
$sql->execute();

$result = $sql->fetchColumn();

if($result != 0) {
    die(-1);
}

$sql = $conn->prepare("INSERT INTO reports (levelID, ip, timestamp) VALUES (:levelID, :ip, UNIX_TIMESTAMP())");
$sql->bindParam(":levelID", $levelID);
$sql->bindParam(":ip", $ip);

$sql->execute();


$webhook = $dw
    ->newMessage()
    ->setTitle("Level Reported!")
    ->setColor("#f03535")
    ->setDescription("Level ID: $levelID")
    ->send();

echo(1);

?>