<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";
include __DIR__ . "/../../config/main.php";

$ml = new mainLib();
$dl = new DashboardLib();

session_start();

$dl->printStyle();
$dl->printHeader();

if(!isset($_SESSION['username'], $_SESSION['password'])) {
    die($dl->printMessageBox3("Access Denied!", "You need to login to use this page!"));
}

if(isset($_POST['songName'])) {

$songName = $_POST['songName'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$songAuthor = $_POST['songAuthor'];
$ip = $_SERVER['REMOTE_ADDR'];

$sql = $conn->prepare("SELECT timestamp FROM actions WHERE ip = :ip AND type = 10 ORDER BY timestamp DESC LIMIT 1");
$sql->execute(['ip' => $ip]);

$result = $sql->fetchColumn();

$minutes = $songReupTime / 60;

# check auth

$authState = $ml->checkAuthentication($username, $password);

if($authState != 1) {
    die($dl->printMessageBox3("Access Denied!", "Invalid login details!"));
}

if($result > time() - $songReupTime) {
    die($dl->printMessageBox("Rate limited!", "You can only reupload a song every $minutes minutes!"));
}

$size = round(filesize($_FILES['songFile']['tmp_name']) / 1000000, 2);

$index = 0;

$songID = mt_rand(9000000, 9999999);

$sql = $conn->prepare("SELECT COUNT(*) FROM songs WHERE id = :id");
$sql->execute([':id' => $songID]);

$result = $sql->fetchColumn();

while($result != 0) {

    $index++;

    $songID = mt_rand(9000000, 9999999);

    $sql = $conn->prepare("SELECT COUNT(*) FROM songs WHERE id = :id");
    $sql->execute([':id' => $songID]);

    $result = $sql->fetchColumn();

    if($index > 99) {
        die($dl->printMessageBox("Failed to find valid ID", "Failed to find a valid song ID after 100 tries!"));
    }

}

# check MIME type

if(mime_content_type($_FILES['songFile']['tmp_name']) != "audio/mpeg") {
    die($dl->printMessageBox("Song failed to upload!", "Please upload an audio file"));
}

$targetDir = __DIR__ . "/../../data/songs/";
$targetFile = $targetDir . $songID . ".mp3";

# Dynamic URL building
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$baseURL = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME'], 3);
$songLink = $baseURL . "/data/songs/" . $songID . ".mp3";

$sql = $conn->prepare("INSERT INTO songs (id, name, authorName, size, download, isReupload) VALUES (:id, :name, :authorName, :size, :download, 1)");
$sql->execute([':id' => $songID, ':name' => $songName, ':authorName' => $songAuthor, ':size' => $size, ':download' => $songLink]);

    if (move_uploaded_file($_FILES['songFile']['tmp_name'], $targetFile)) {
        echo $dl->printMessageBox3("Song uploaded!", "Your song has been uploaded with an ID of <strong>$songID</strong>");
    } else {
        die($dl->printMessageBox("Song failed to upload!", "The song failed to upload. Try again later."));
    }

    $ml->logAction(10, $songName, $songAuthor, $size);

} else {
    $dl->printSongReupForm();
}

?>