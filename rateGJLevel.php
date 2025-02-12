<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/incl/lib/DiscordWebhook.php";
include __DIR__ . "/incl/lib/mainLib.php";
include __DIR__ . "/config/webhooks.php";
include __DIR__ . "/incl/lib/exploitPatch.php";

$ml = new mainLib();

$dw = new DiscordWebhook($diffRateWebhook);

$levelID = exploitPatch::clean($_POST['levelID']);
$rating = exploitPatch::clean($_POST['rating']);
$secret = exploitPatch::clean($_POST['secret']);
$ip = $_SERVER['REMOTE_ADDR'];

# secret check

if($secret != "Wmfd2893gb7") {
    exit(-1);
}

# check if you have already rated, anti spam protection

$sql = $conn->prepare("SELECT count(*) FROM actions WHERE type = 2 AND value1 = :value1 AND ip = :ip");
$sql->execute([':value1' => $levelID, ':ip' => $ip]);

$count = $sql->fetchColumn();

if($count != 0) {
    die("1");
}

# send webhook

$ratesWebhook = $dw
    ->newMessage()
    ->setTitle("User Rated Difficulty!")
    ->setDescription("Level ID: $levelID\n Diff: $rating")
    ->setColor("#a9ffa1")
    ->send();

# log action

$ml->logAction(2, $levelID, $rating);

echo(1);

?>