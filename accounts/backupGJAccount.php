<?php

include __DIR__ . "/../incl/lib/connection.php";
include __DIR__ . "/../incl/lib/gjp.php";
include __DIR__ . "/../incl/lib/mainLib.php";
include __DIR__ . "/../incl/lib/exploitPatch.php";

$gjpTools = new gjpTools();
$ml = new MainLib();

# Gathering data

$userName = exploitPatch::clean($_POST['userName']);
$password = exploitPatch::clean($_POST['password']);
$saveData = $_POST['saveData'];
$secret = exploitPatch::clean($_POST['secret']);

# Large file spam prevention

ini_set("upload_max_filesize","50M");

# Actual code

$password_gjp = $gjpTools->MakeGJP($password);

# Check if credentials are valid

$sql = $conn->prepare("SELECT COUNT(*) FROM accounts WHERE username = :username AND password = :password");
$sql->bindParam(":username", $userName);
$sql->bindParam(":password", $password_gjp);
$sql->execute();

$result = $sql->fetchColumn();

if($result != 1) {
    die("-1");
}

# Find account ID

$sql = $conn->prepare("SELECT id FROM accounts WHERE username = :username AND password = :password");
$sql->bindParam(":username", $userName);
$sql->bindParam(":password", $password_gjp);
$sql->execute();

$result = $sql->fetchColumn();

# create a file with the player's save data, with the name of their account ID

file_put_contents(__DIR__ . "/../data/users/" . $result, $saveData);

# log action

$ml->logAction(3, $userName);

echo(1);

?>