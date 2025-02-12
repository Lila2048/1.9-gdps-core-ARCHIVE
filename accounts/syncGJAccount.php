<?php

include __DIR__ . "/../incl/lib/connection.php";
include __DIR__ . "/../incl/lib/gjp.php";
include __DIR__ . "/../incl/lib/mainLib.php";
include __DIR__ . "/../incl/lib/exploitPatch.php";

$gjpTools = new gjpTools();
$ml = new mainLib();

# getting the data from params

$userName = exploitPatch::clean($_POST['userName']);
$password = exploitPatch::clean($_POST['password']);
$secret = exploitPatch::clean($_POST['secret']);

# actual code

$password_gjp = $gjpTools->MakeGJP($password);

$sql = $conn->prepare("SELECT COUNT(*) FROM accounts WHERE username = :username AND password = :password");
$sql->bindParam(":username", $userName);
$sql->bindParam(":password", $password_gjp);
$sql->execute();

$result = $sql->fetchColumn();

if($result != 1) {
    die("-1");
}

# resolve account ID

$sql = $conn->prepare("SELECT id FROM accounts WHERE username = :username AND password = :password");
$sql->bindParam(":username", $userName);
$sql->bindParam(":password", $password_gjp);
$sql->execute();

$accountID = $sql->fetchColumn();

# if we made it here, return the data

echo(file_get_contents(__DIR__ . "/../data/users/" . $accountID));

# log action

$ml->logAction(4, $userName);

?>