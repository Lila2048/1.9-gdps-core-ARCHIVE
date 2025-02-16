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
        # fetch reported levels
        $sql = $conn->prepare("SELECT levelID, timestamp FROM reports LIMIT 100");
        $sql->execute();

        $reports = $sql->fetchAll(PDO::FETCH_ASSOC);

        if(empty($reports)) {
            die($dl->printMessageBox("Error!", "No reported levels found!"));
        }

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reported Levels List</title>
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
            <h1 class="title">Reported Levels List</h1>
            <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Level ID</th>
                        <th>Report Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['levelID']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', $report['timestamp'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </body>
        </html>