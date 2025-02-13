<h1>GDPS Dashboard</h1>

<?php

session_start();

include __DIR__ . "/../incl/lib/mainLib.php";
include __DIR__ . "/../incl/lib/dashboardLib.php";

$permLevel = 0;
$isAuthenticated = false;

$ml = new MainLib();
$dl = new DashboardLib();
if (isset($_POST['username'], $_POST['password'])) {
    $authState = $ml->checkAuthentication($_POST['username'], $_POST['password']);
    if ($authState != 1) {
        echo("<h3>Invalid login info!</h3>");
    } else {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        header("Location: index.php");
        exit();
    }
}

if (isset($_SESSION['username'], $_SESSION['password']) && !isset($_POST['username'], $_POST['password'])) {
    $authState = $ml->checkAuthentication($_SESSION['username'], $_SESSION['password']);
    if ($authState != 1) {
        $dl->printLoginForm();
        echo("<h3>Invalid login info! Logged out!</h3>");
        session_destroy();
    } else {
        echo("<h3>Logged in as: " . $_SESSION['username'] . " <a href='api/logout.php'>Logout</a></h3>");
        $accID = $ml->getAccountID($_SESSION['username']);
        $udid = $ml->getUDIDFromAccountID($accID);
        $userID = $ml->getUserID($udid);
        $userInfo = $ml->getUserStats($userID);
        if(isset($userInfo['permLevel'])) {
        $permLevel = $userInfo['permLevel'];
        $isAuthenticated = true;
        }
    }
} else {
    echo("<h2>Please log in to manage your account/use moderation features</h2>");
    $dl->printLoginForm();
}
?>

<html>
<body>

<?php 
    if($isAuthenticated == true) {
        echo "<h2>User Actions</h2>
        <ul>
            <li><a href='user/uploadSong.php'>Upload Song</a></li>
            <li><a href='user/reuploadsTable.php'>Reuploaded Songs</a></li>
            <li><a href='user/changeUsername.php'>Change Username</a></li>
            <li><a href='user/changePassword.php'>Change Password</a></li>
            <li><a href='user/changeEmail.php'>Change Email</a></li>
        </ul>";
    }

    if($permLevel > 0) { 
        echo "
        <h2>Moderator Actions</h2>
        <ul>
            <li><a href='mod/banUser.php'>Ban User</a></li>
            <li><a href='mod/bansList.php'>Bans List</a></li>
            <li><a href='mod/sentList.php'>Sent List</a></li>
            <li><a href='mod/sendLevel.php'>Send Level</a></li>
            <li><a href='mod/reportsList.php'>Reports List</a></li>
        </ul>
        ";
    }
    if($permLevel > 1) {
    echo "
        <h2>Admin Actions</h2>
        <ul>
            <li>Reset User Password</li>
            <li>Force Change Username</li>
            <li>Force Change Email</li>
            <li>Rate level</li>
            <li><a href='admin/runCron.php'>Run Cron</a></li>
        </ul>
        ";
}
?>
    </body>
</html>