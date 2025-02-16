<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../config/dashboard.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$dl = new DashboardLib();
$ml = new MainLib();

$dl->printStyle();
$dl->printHeader();

$time = time();

# check perms

if($dl->checkPermsLevel() < 0) {
    die($dl->printMessageBox3("Access Denied!", "You are either not logged in or do not have the appropriate permission to access this tool."));
}

$sql = $conn->prepare("SELECT * FROM bans WHERE expires > :time");
$sql->execute([':time' => $time]);

$bans = $sql->fetchAll(PDO::FETCH_ASSOC);

if(empty($bans)) {
    die($dl->printMessageBox("Error!", "No banned users found!"));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banned Users</title>
    <style>
        a {
            color: white;
        }
        .table-container {
            max-width: 70%;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
            background-color: #2e2e2e;
            color: rgb(177, 177, 177);
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #5e5e5e;
        }
        table th {
            background-color: #3e3e3e;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #2a2a2a;
        }
        table tr:nth-child(odd) {
            background-color: #2e2e2e;
        }
        input[type="submit"] {
            background-color: #7b7b7b;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #676767;
        }
    </style>
</head>
<body>
    <h1 class="title">Banned Users</h1>
    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Ban ID</th>
                <th>UserID</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Expires</th>
                <th>Banned At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bans as $ban): ?>
            <tr>
                <?php switch($ban['banType']) {case 1: $ban['banType'] = "Account"; break; case 2: $ban['banType'] = "Uploading"; break; case 3: $ban['banType'] = "Commenting"; break; case 4: $ban['banType'] = "Top Creators"; break; case 5: $ban['banType'] = "Top Players"; break;} ?>
                <td><?php echo htmlspecialchars($ban['id']); ?></td>
                <td><?php echo htmlspecialchars($ban['user']); ?></td>
                <td><?php echo htmlspecialchars($ban['banType']); ?></td>
                <td><?php echo htmlspecialchars(base64_decode($ban['reason'])); ?></td>
                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', $ban['expires'])); ?></td>
                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', $ban['timestamp'])); ?></td>
                <td>
                    <form action="<?php echo $dashPath; ?>/mod/unbanUser.php" method="POST">
                        <input type="hidden" name="banID" value="<?php echo htmlspecialchars($ban['id']); ?>">
                        <input type="submit" value="Unban">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>