<?php

include __DIR__ . "/../../incl/lib/connection.php";

$sql = $conn->prepare("SELECT id, name, authorName, download FROM songs WHERE isReupload = 1 AND isBanned = 0 LIMIT 100");
$sql->execute();

$songs = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuploads Table</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Reuploads Table</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Artist</th>
                <th>Link</th>
            </tr>
        </thead>
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
</body>
</html>