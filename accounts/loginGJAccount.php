<?php

include __DIR__ . "/../incl/lib/connection.php";
include __DIR__ . "/../incl/lib/gjp.php";
include __DIR__ . "/../incl/lib/mainLib.php";

$gjpTools = new gjpTools();
$ml = new MainLib();

# TODO: Move most of this logic to functions

# Gathering data

$udid = $_POST['udid'];
$userName = $_POST['userName'];
$passRaw = $_POST['password'];
$secret = $_POST['secret'];
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];

# Secret check

if($secret != "Wmfv3899gc9") {
    die(-1);
}

# Checking authentication

$pass = $gjpTools->MakeGJP($passRaw);

$sql = $conn->prepare("SELECT COUNT(*) FROM accounts WHERE username = :username AND password = :password");
$sql->bindParam(":username", $userName);
$sql->bindParam(":password", $pass);
$sql->execute();

$result = $sql->fetchColumn();

if($result == 0) {
    die(-11);
} else {

    # check account ID
    
    $sql = $conn->prepare("SELECT id FROM accounts WHERE username = :username and password = :password");
    $sql->bindParam(":username", $userName);
    $sql->bindParam(":password", $pass);
    $sql->execute();

    $accID = $sql->fetchColumn();

    # Check if account has a linked user

    $sql = $conn->prepare("SELECT COUNT(*) FROM users WHERE accountID = :accountID");
    $sql->bindParam(":accountID", $accID);
    $sql->execute();

    $udid_check = $sql->fetchColumn();

    if($udid_check == 0) {
        $sql = $conn->prepare("INSERT INTO users (udid, userName, accountID) VALUES (:udid, :userName, :accountID)");
        $sql->bindParam(":udid", $udid);
        $sql->bindParam(":userName", $userName);
        $sql->bindParam(":accountID", $accID);

        $sql->execute();
    }

    # Get userID

    $sql = $conn->prepare("SELECT userID FROM users WHERE udid = :udid");
    $sql->bindParam(":udid", $udid);
    $sql->execute();

    $userID = $sql->fetchColumn();

    # Mark the account as registered

    $sql = $conn->prepare("UPDATE users SET isRegistered = 1 WHERE udid = :udid");
    $sql->bindParam(":udid", $udid);

    $sql->execute();

    # Change the login time to current time

    $sql = $conn->prepare("UPDATE accounts SET lastlogin = :time WHERE id = :accountID");
    $sql->bindParam(":time", $time);
    $sql->bindParam(":accountID", $accID);

    $sql->execute();

    # change the IP of the account to the device ip

    $sql = $conn->prepare("UPDATE accounts SET ip = :ip WHERE id = :accountID");
    $sql->bindParam(":ip", $ip);
    $sql->bindParam(":accountID", $accID);
    
    $sql->execute();

    $sql = $conn->prepare("UPDATE accounts SET udid = :udid WHERE id = :accountID");
    $sql->bindParam(":udid", $udid);
    $sql->bindParam(":accountID", $accID);
    
    $sql->execute();

    $sql = $conn->prepare("UPDATE users SET udid = :udid WHERE accountID = :accountID");
    $sql->bindParam(":udid", $udid);
    $sql->bindParam(":accountID", $accID);
    
    $sql->execute();

    # link levels to new uid

    $sql = $conn->prepare("UPDATE levels SET udid = :udid, userName = :userName, accountID = :accountID WHERE userID = :userID");
    $sql->execute([':userID' => $userID, ':udid' => $udid, ':userName' => $userName, ':accountID' => $accID]);

    # update comments

    $sql = $conn->prepare("UPDATE comments SET udid = :udid, userName = :userName, accountID = :accountID WHERE userID = :userID");
    $sql->execute([':userID' => $userID, ':udid' => $udid, ':userName' => $userName, ':accountID' => $accID]);

    echo($accID . "," . $userID);

    $ml->logAction(5, $userName, $udid);
}

?>