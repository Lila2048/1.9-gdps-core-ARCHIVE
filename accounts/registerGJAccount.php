<?php

include __DIR__ . "/../incl/lib/connection.php";
include __DIR__ . "/../incl/lib/gjp.php";
include __DIR__ . "/../incl/lib/mainLib.php";
include __DIR__ . "/../incl/lib/exploitPatch.php";

$ml = new MainLib();

# Getting data

$uname = exploitPatch::clean($_POST['userName']);
$pass = exploitPatch::clean($_POST['password']);
$email = exploitPatch::clean($_POST['email']);
$secret = exploitPatch::clean($_POST['secret']);
$ip = $_SERVER['REMOTE_ADDR'];

# Secret check

if($secret != "Wmfv3899gc9") {
    die("-1");
}

# username check

$sql = $conn->prepare("SELECT COUNT(*) FROM accounts WHERE username = :username");
$sql->bindParam(":username", $uname);
$sql->execute();

$result = $sql->fetchColumn();

if($result != 0) {
    die("-2");
}

# email check

$sql = $conn->prepare("SELECT COUNT(*) FROM accounts WHERE email = :email");
$sql->bindParam(":email", $email);
$sql->execute();

$result = $sql->fetchColumn();

if($result != 0) {
    die("-3");
}

# username too long check

if(mb_strlen($uname) > 20) {
    die("-4");
}

# username too short check

if(mb_strlen($uname) < 3) {
    die("-9");
}

# password too short check

if(mb_strlen($pass) < 6) {
    die("-8");
}

# if we made it down here everything is valid, return 1 for success

$gjpTools = new gjpTools();
$gjp = $gjpTools->MakeGJP($pass);
$time = time();
$lastlogin = 0;

$sql = $conn->prepare("INSERT INTO accounts (username, email, password, regdate, lastlogin, ip) VALUES (:username, :email, :pass, :regdate, :lastlogin, :ip)");
$sql->bindParam(":username", $uname);
$sql->bindParam(":email", $email);
$sql->bindParam(":pass", $gjp);
$sql->bindParam(":regdate", $time);
$sql->bindParam(":lastlogin", $lastlogin);
$sql->bindParam(":ip", $ip);
$sql->execute();

$ml->logAction(6, $uname, $email);

echo("1");

?>