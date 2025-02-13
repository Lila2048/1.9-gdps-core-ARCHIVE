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
        # fetch banned users
        $sql = $conn->prepare("SELECT user, banType, expires, reason, timestamp FROM bans");
        $sql->execute();

        $bans = $sql->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Banned Users List</title>
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
            <h1>Banned Users List</h1>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Ban Type</th>
                        <th>Reason</th>
                        <th>Expires</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bans as $ban): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ban['user']); ?></td>
                            <td><?php echo htmlspecialchars($ban['banType']); ?></td>
                            <td><?php echo htmlspecialchars(base64_decode($ban['reason'])); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', $ban['expires'])); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', $ban['timestamp'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </body>
        </html>
        <?php
    }

?>