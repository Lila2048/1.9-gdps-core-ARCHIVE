<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$dl = new DashboardLib();
$ml = new MainLib();

$dl->printStyle();
$dl->printHeader();

$sql = $conn->prepare("SELECT * FROM mappacks ORDER BY difficulty DESC");
$sql->execute();

$packs = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Packs</title>
    <style>
    </style>
</head>
<body>
    <h1 class="title">Map Packs</h1>
    <div class="table-container">
    <table>
        <tr>
            <tr>
                <th>Name</th>
                <th>Levels</th>
                <th>Stars</th>
                <th>Coins</th>
            </tr>
        </tr>
        <tbody>
            <?php foreach ($packs as $pack): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pack['packName']); ?></td>
                    <td><?php echo htmlspecialchars(str_replace(",", ", ", $pack['levels'])); ?></td>
                    <td><?php echo htmlspecialchars($pack['stars']); ?></td>
                    <td><?php echo htmlspecialchars($pack['coins']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>