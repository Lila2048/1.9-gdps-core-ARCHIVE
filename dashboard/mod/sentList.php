<?php

    # check auth
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";

    session_start();

    if(!isset($_SESSION['username'], $_SESSION['password'])) {
        die("<h1>Access denied!</h1>");
    }

    $ml = new MainLib();

    $accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
    $udid = $ml->getUDIDFromAccountID($accountID);
    $permState = $ml->checkPerms(2, $udid);

    if($permState != 1) {
        die("<h1>Access denied!</h1>");
    } else {
            # fetch sent levels
            $sql = $conn->prepare("SELECT levelID, stars, feature, timestamp FROM sends ORDER BY timestamp DESC LIMIT 100");
            $sql->execute();
    
            $levels = $sql->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Sent Levels List</title>
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
                <h1>Sent Levels List</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Level ID</th>
                            <th>Stars</th>
                            <th>Feature</th>
                            <th>Timestamp</th>
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
            </body>
            </html>
        <?php
    }

?>