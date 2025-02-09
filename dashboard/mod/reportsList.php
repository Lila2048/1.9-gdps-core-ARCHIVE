<?php

if(isset($_POST['username'], $_POST['password'])) {
    # check auth
    include __DIR__ . "/../../incl/lib/connection.php";
    include __DIR__ . "/../../incl/lib/mainLib.php";

    $ml = new MainLib();

    $userName = $_POST['username'];
    $password = $_POST['password'];

    $accountID = $ml->getAccountID($userName, $password);
    $udid = $ml->getUDIDFromAccountID($accountID);
    $permState = $ml->checkPerms(1, $udid);

    if($permState != 1) {
        displayForm();
        die("<h1>Missing perms or invalid login!</h1>");
    } else {
        # fetch reported levels
        $sql = $conn->prepare("SELECT levelID, timestamp FROM reports LIMIT 100");
        $sql->execute();

        $reports = $sql->fetchAll(PDO::FETCH_ASSOC);
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
            <h1>Reported Levels List</h1>
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
        </body>
        </html>
        <?php
    }
} else {
    # display auth form
    displayForm();
}

function displayForm() {
    echo "<form action='reportsList.php' method='POST'>
    <label for='username'>Username:</label>
    <input type='text' name='username' id='username' required>
    <br>
    <label for='password'>Password:</label>
    <input type='password' name='password' id='password' required>
    <br>
    <input type='submit'>
    </form>";
}

?>