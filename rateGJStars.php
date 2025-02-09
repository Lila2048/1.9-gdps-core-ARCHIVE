<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/DiscordWebhook.php";
include __DIR__ . "/config/webhooks.php";
include __DIR__ . "/incl/lib/mainLib.php";

$dw = new DiscordWebhook($userRateWebhook);

$levelID = $_POST['levelID'];
$rating = $_POST['rating'];
$secret = $_POST['secret'];
$ip = $_SERVER['REMOTE_ADDR'];

$ml = new MainLib();

# secret check

if($secret != "Wmfd2893gb7") {
    exit(-1);
}

# check if you have already rated, anti spam protection

$sql = $conn->prepare("SELECT count(*) FROM actions WHERE type = 1 AND value1 = :value1 AND ip = :ip");
$sql->execute([':value1' => $levelID, ':ip' => $ip]);

$count = $sql->fetchColumn();

if($count != 0) {
    die(1);
}

# send webhook

$ratesWebhook = $dw
    ->newMessage()
    ->setTitle("User Rated Level!")
    ->setDescription("Level ID: $levelID\n Stars: $rating")
    ->setColor("#fff769")
    ->send();

# log action

$ml->logAction(1, $levelID, $rating);

echo(1);

?>