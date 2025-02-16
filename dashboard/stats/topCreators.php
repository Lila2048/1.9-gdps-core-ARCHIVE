<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$dl = new DashboardLib();
$ml = new MainLib();

$dl->printStyle();
$dl->printHeader();

$rank = 1;

$bansCsv = $ml->getCommaSeparatedBans(5);

$queryString = "SELECT stars, userName, demons, coins, creatorPoints FROM users WHERE creatorPoints > 0";

if($bansCsv != "") {
    $queryString .= " AND userID NOT IN ($bansCsv)";
}

$sql = $conn->prepare($queryString . " ORDER BY creatorPoints DESC LIMIT 100");
$sql->execute();

$users = $sql->fetchAll(PDO::FETCH_ASSOC);

if(empty($users)) {
    die($dl->printMessageBox("Error!", "There doesn't seem to be any users with creator points..."));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Creators Leaderboard</title>
    <style>
    </style>
</head>
<body>
    <h1 class="title">Top Creators Leaderboard</h1>
    <div class="table-container">
    <table>
        <tr>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>CP</th>
            </tr>
        </tr>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo "#".$rank; $rank++; ?></td>
                    <td><?php echo htmlspecialchars($user['userName']); ?></td>
                    <td><?php echo htmlspecialchars($user['creatorPoints']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>