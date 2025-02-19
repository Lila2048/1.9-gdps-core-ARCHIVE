<?php

include __DIR__ . "/incl/lib/connection.php";
include __DIR__ . "/config/main.php";

$udid = $_POST['udid'];
$accountID = $_POST['accountID'];
$commentID = $_POST['commentID'];
$secret = $_POST['secret'];

if($accountID == 0 && $requireAuthentication == true) {
    die("-1");
}

# secret check

if($secret != "Wmfd2893gb7") {
    die(-1);
}

# check comment ownership

$sql = $conn->prepare("SELECT udid FROM comments WHERE id = :commentID");
$sql->execute([':commentID' => $commentID]);

$result = $sql->fetchColumn();

if($result != $udid) {
    die("-1");
}

# delete comment

$sql = $conn->prepare("DELETE FROM comments WHERE id = :commentID");
$sql->execute([':commentID' => $commentID]);

echo(1);

?>