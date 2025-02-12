<?php

include __DIR__ . "/incl/lib/connection.php";

# Getting data

$page = $_POST['page'];
$levelID = $_POST['levelID'];
$secret = $_POST['secret'];

$page2 = $page*5;

$commentString = "";
$userString = "";

# Secret check

if($secret != "Wmfd2893gb7") {
    die("-1");
}

$sql = $conn->prepare("SELECT * FROM comments WHERE levelID = :levelID LIMIT 5 OFFSET $page2");
$sql->bindParam(":levelID", $levelID);

$sql->execute();

$result = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $comment) {
    $commentString .= "1~" . $comment['levelID'] . "~2~" . base64_decode($comment['comment']). "~3~" . $comment['userID'] . "~4~" . $comment['likes'] . "~5~0" . "~6~" . $comment['id'] . "~7~" . $comment['spam'] . "~8~" . $comment['accountID'] . "|";
}

$commentString = rtrim($commentString, "|");

foreach($result as $userComment) {
    $userString .= $userComment['userID'] . ":" . $userComment['userName'] . ":" . $userComment['accountID'] . "|";
}

$userString = rtrim($userString, "|");

# query number of comments

$sql = $conn->prepare("SELECT count(*) FROM comments WHERE levelID = :levelID");
$sql->bindParam(":levelID", $levelID);
$sql->execute();
$commentCount = $sql->fetchColumn();

if($commentCount != 0) {

echo($commentString . "#" . $userString . "#" . $commentCount . ":" . $page2 . ":5");

} else {
    echo("-2");
}

?>