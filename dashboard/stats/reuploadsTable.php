<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$dl = new DashboardLib();
$ml = new MainLib();

$dl->printStyle();
$dl->printHeader();

$sql = $conn->prepare("SELECT id, name, authorName, download FROM songs WHERE isReupload = 1 AND isBanned = 0 LIMIT 100");
$sql->execute();

$songs = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuploaded Songs</title>
    <style>
        a {
            color:white;
        }
    </style>
</head>
<body>
    <h1 class="title">Reuploads Table</h1>
    <div class="table-container">
    <table>
        <tr>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Artist</th>
                <th>Link</th>
            </tr>
        </tr>
        <tbody>
            <?php foreach ($songs as $song): ?>
                <tr>
                    <td><?php echo htmlspecialchars($song['id']); ?></td>
                    <td><?php echo htmlspecialchars($song['name']); ?></td>
                    <td><?php echo htmlspecialchars($song['authorName']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($song['download']); ?>" target="_blank">Download</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>