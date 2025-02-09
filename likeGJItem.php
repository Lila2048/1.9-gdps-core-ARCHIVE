<?php

include __DIR__ . "/incl/lib/connection.php";

$itemID = $_POST['itemID'];
$like = $_POST['like'];
$type = $_POST['type'];
$secret = $_POST['secret'];
$ip = $ip = $_SERVER['REMOTE_ADDR'];
$timestamp = time();

# almost forgot to do secret check lol

if($secret != "Wmfd2893gb7") {
    exit(-1);
}

switch($type) {
    case 1:

        # levels

        # make sure this IP hasn't liked the content before

        $sql = $conn->prepare("SELECT COUNT(*) FROM actions_likes WHERE ip = :ip AND type = :type AND itemID = :itemID");
        $sql->execute([':ip' => $ip, ':type' => $type, ':itemID' => $itemID]);

        $result  = $sql->fetchColumn();

        if($result != 0) {
            exit(-1);
        }

        # get current like count

        $sql = $conn->prepare("SELECT likes FROM levels WHERE levelID = :levelID");
        $sql->bindParam(":levelID", $itemID);
        $sql->execute();

        $result = $sql->fetchColumn();

        if($like == 1){
            echo(1);
            # like level

            # update like count in levels table

            $likes = $result + 1;
            $sql = $conn->prepare("UPDATE levels SET likes = $likes  WHERE levelID = :itemID");
            $sql->bindParam(":itemID", $itemID);
            $sql->execute();

            # Log action

            $sql = $conn->prepare("INSERT INTO actions_likes (itemID, type, isLike, ip, timestamp) VALUES (:itemID, :type, :isLike, :ip, :timestamp)");
            $sql->execute([':itemID' => $itemID, ':type' => $type, ':isLike' => $like, ':ip' => $ip, ':timestamp' => $timestamp]);
        } else {
            echo(1);
            # dislike level

            # update like count in levels table

            $likes = $result - 1;
            $sql = $conn->prepare("UPDATE levels SET likes = $likes  WHERE levelID = :itemID");
            $sql->bindParam(":itemID", $itemID);
            $sql->execute();
            # log action

            $sql = $conn->prepare("INSERT INTO actions_likes (itemID, type, isLike, ip, timestamp) VALUES (:itemID, :type, :isLike, :ip, :timestamp)");
            $sql->execute([':itemID' => $itemID, ':type' => $type, ':isLike' => $like, ':ip' => $ip, ':timestamp' => $timestamp]);
            echo(1);
        }
        break;
    case 2:

        # comments ig

        # make sure this IP hasn't liked the comment before

        $sql = $conn->prepare("SELECT COUNT(*) FROM actions_likes WHERE ip = :ip AND type = :type AND itemID = :itemID");
        $sql->execute([':ip' => $ip, ':type' => $type, ':itemID' => $itemID]);

        $result  = $sql->fetchColumn();

        if($result != 0) {
            exit(-1);
        }

        # get current like count

        $sql = $conn->prepare("SELECT likes FROM comments WHERE id = :commentID");
        $sql->bindParam(":commentID", $itemID);
        $sql->execute();

        $result = $sql->fetchColumn();

        if($like == 1){
            # like level

            # update like count in comments table

            $likes = $result + 1;
            $sql = $conn->prepare("UPDATE comments SET likes = $likes  WHERE id = :itemID");
            $sql->bindParam(":itemID", $itemID);
            $sql->execute();

            # Log action

            $sql = $conn->prepare("INSERT INTO actions_likes (itemID, type, isLike, ip, timestamp) VALUES (:itemID, :type, :isLike, :ip, :timestamp)");
            $sql->execute([':itemID' => $itemID, ':type' => $type, ':isLike' => $like, ':ip' => $ip, ':timestamp' => $timestamp]);
            echo(1);
        } else {
            # dislike level

            # update like count in comments table

            $likes = $result - 1;
            $sql = $conn->prepare("UPDATE comments SET likes = $likes  WHERE id = :itemID");
            $sql->bindParam(":itemID", $itemID);
            $sql->execute();
            echo(1);

            # log action

            $sql = $conn->prepare("INSERT INTO actions_likes (itemID, type, isLike, ip, timestamp) VALUES (:itemID, :type, :isLike, :ip, :timestamp)");
            $sql->execute([':itemID' => $itemID, ':type' => $type, ':isLike' => $like, ':ip' => $ip, ':timestamp' => $timestamp]);
            echo(1);
        }
        break;
}

?>