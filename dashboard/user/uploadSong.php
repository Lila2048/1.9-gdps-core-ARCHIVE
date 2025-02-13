<?php

session_start();

if(!isset($_SESSION['username'], $_SESSION['password'])) {
    die("<h1>Access denied<h1>");
}

if(isset($_POST['songName'])) {
include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../config/main.php";
include __DIR__ . "/../../incl/lib/mainLib.php";

$ml = new mainLib();

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
    die("<h1>Invalid details!</h1>");
}

if($result > time() - $songReupTime) {
    echo("<h1>Rate limited! You may only reupload a song every $minutes minutes!</h1><button onClick='window.location.reload();'>Try again!</button><button onclick='window.location.replace(location.pathname);'>Back</button>");
    die();
}

$size = 0.1;

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

    echo("searching for valid song ID. Iteration #" . $index . "<br>");

    if($index > 99) {
        echo("<h1> failed to find a valid song ID after 100 tries! try again later!</h1><button onClick='window.location.reload();'>Try again!</button><button onclick='window.location.replace(location.pathname);'>Back</button>");
        die(-1);
    }

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
        echo "<h2>The song file " . basename($_FILES['songFile']['name']) . " has been uploaded with an ID of $songID.<br>";
        echo "You can access the song at: <a href='$songLink'>$songLink</a></h2><button onclick='window.location.replace(location.pathname);'>Back</button>";
    } else {
        echo "<h1>Song failed to upload!</h1><button onClick='window.location.reload();'>Try again!</button><button onclick='window.location.replace(location.pathname);'>Back</button>";
    }

    $ml->logAction(10, $songName, $songAuthor, $size);

} else echo "<h1>Upload Song</h1>
<form action='uploadSong.php' enctype='multipart/form-data' method='POST'>
    <label for='songFile'>Song File:</label>
    <input type='file' name='songFile' id='songFile' accept='.mp3' required>
    <br>
    <label for='songName'>Song Name:</label>
    <input type='text' name='songName' maxlength='30' id='songName' required>
    <br>
    <label for='songAuthor'>Song Author:</label>
    <input type='text' name='songAuthor' maxlength='15' id='songAuthor' required>
    <br>
    <input type='submit'>
</form>";

?>