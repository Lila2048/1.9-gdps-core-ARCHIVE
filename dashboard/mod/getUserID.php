<?php

include __DIR__ . "/../../incl/lib/connection.php";
include __DIR__ . "/../../incl/lib/mainLib.php";
include __DIR__ . "/../../incl/lib/exploitPatch.php";
include __DIR__ . "/../../incl/lib/dashboardLib.php";

session_start();

$ml = new mainLib();
$dl = new DashboardLib();

ob_start(); // Start output buffering

$dl->printStyle();

if(!isset($_SESSION['username'], $_SESSION['password'])) {
    ob_end_clean(); // Clear the buffer
    $dl->printStyle();
    $dl->printHeader();
    die($dl->printMessageBox3("Access Denied!", "You need to sign in to view this page!"));
}

$accountID = $ml->getAccountID($_SESSION['username'], $_SESSION['password']);
$udid = $ml->getUDIDFromAccountID($accountID);
$permState = $ml->checkPerms(1, $udid);

if($permState != 1) {
    ob_end_clean(); // Clear the buffer
    die($dl->printMessageBox3("Access Denied!", "You do not have the appropriate permissions to use this tool!"));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchUsername'])) {
    $searchUsername = exploitPatch::clean($_POST['searchUsername']);
    $_SESSION['searchUsername'] = $searchUsername;
    header('Location: getUserID.php');
    exit();
}

ob_end_flush(); // Flush the buffer and send output

$dl->printHeader();

if (isset($_SESSION['searchUsername'])) {
    $searchUsername = $_SESSION['searchUsername'];
    unset($_SESSION['searchUsername']);

    $sql = $conn->prepare("SELECT * FROM users WHERE userName = :username");
    $sql->execute([':username' => $searchUsername]);

    $users = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UserID Search</title>
    <link rel="stylesheet" href="../../incl/style/dashboard.css">
</head>
<body>
    <h1 class="title">UserID Search</h1>
    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>UserID</th>
                <th>Username</th>
                <th>AccountID</th>
                <th>Stars</th>
                <th>Demons</th>
                <th>Coins</th>
                <th>Last Submit</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['userID']); ?></td>
                <td><?php echo htmlspecialchars($user['userName']); ?></td>
                <td><?php echo htmlspecialchars($user['accountID']); ?></td>
                <td><?php echo htmlspecialchars($user['stars']); ?></td>
                <td><?php echo htmlspecialchars($user['demons']); ?></td>
                <td><?php echo htmlspecialchars($user['coins']); ?></td>
                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', $user['time'])); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>
<?php
} else {
    $dl->printUserIDFindForm();
}
?>