<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$dl = new DashboardLib();
$ml = new MainLib();

$dl->printStyle();
$dl->printHeader();

# check perms

if($dl->checkPermsLevel() < 0) {
    die($dl->printMessageBox3("Access Denied!", "You are either not logged in or do not have the appropriate permission to access this tool."));
}
            # fetch sent levels
            $sql = $conn->prepare("SELECT levelID, stars, feature, timestamp FROM sends ORDER BY timestamp DESC LIMIT 100");
            $sql->execute();
    
            $levels = $sql->fetchAll(PDO::FETCH_ASSOC);

            if(empty($levels)) {
                die($dl->printMessageBox("Error!", "No sent levels found!"));
            }
        ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Sent Levels List</title>
            </head>
            <div>
                <h1 class="title">Sent Levels List</h1>
                <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Level ID</th>
                            <th>Stars</th>
                            <th>Feature</th>
                            <th>Sent At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($levels as $level): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($level['levelID']); ?></td>
                                <td><?php echo htmlspecialchars($level['stars']); ?></td>
                                <td><?php echo htmlspecialchars($level['feature']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', $level['timestamp'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </body>
            </html>