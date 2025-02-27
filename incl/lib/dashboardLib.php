<?php

include __DIR__ . '/../../config/dashboard.php';

global $dbPath;
$dbPath = $dashPath;

class DashboardLib {

    public function __construct() {
        echo('<head><script src="https://kit.fontawesome.com/7013093b07.js" crossorigin="anonymous"></script><title>GDPS Dashboard</title></head>');
    }

    public function printLoginForm() {
        echo '<form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit">
        </form>';
    }

    public function printHeader() {
        include_once __DIR__ . "/mainLib.php";
    
        $ml = new mainLib();
    
        $isLogin = false;
    
        global $dbPath;
    
        $headerString = '<div class="header">
        <div class="left-menu">
            <div class="dropdown">
                <a href="'. $dbPath . '" class="dropbtn"><i class="fas fa-home"></i>Home</a>
            </div>';
    
        if(isset($_SESSION['username'], $_SESSION['password'])) {
            $isLogin = true;
            $accID = $ml->getAccountID($_SESSION['username']);
            $udid = $ml->getUDIDFromAccountID($accID);
            $userID = $ml->getUserID($udid);
            $userInfo = $ml->getUserStats($userID);
            $headerString .= '<div class="dropdown">
                <a class="dropbtn"><i class="fas fa-user"></i>User</a>
                <div class="dropdown-content">
                    <a href="'. $dbPath . '/user/changeUsername.php">Change Username</a>
                    <a href="'. $dbPath . '/user/changePassword.php">Change password</a>
                    <a href="'. $dbPath . '/user/changeEmail.php">Change Email</a>
                </div>
            </div>';
    
            $headerString .= '<div class="dropdown">
            <a class="dropbtn"><i class="fa-solid fa-upload"></i>Reupload</a>
            <div class="dropdown-content">
                <a href="'. $dbPath . '/reupload/uploadSong.php">Upload Song</a>
                <a href="'. $dbPath . '/reupload/levelToGMD.php">Level To GMD</a>
                <!--<a href="'. $dbPath . '/reupload/levelReupload.php">Level Reupload</a>-->
            </div>
        </div>';
    
            if($userInfo['permLevel'] > 0) {
                $headerString .= '<div class="dropdown">
                    <a class="dropbtn"><i class="fa-solid fa-shield-halved"></i>Moderation</a>
                    <div class="dropdown-content">
                        <a href="'. $dbPath . '/mod/banUser.php">Ban User</a>
                        <a href="'. $dbPath . '/mod/bansList.php">Bans List</a>
                        <a href="'. $dbPath . '/mod/reportsList.php">Reported Levels</a>
                        <a href="'. $dbPath . '/mod/sendLevel.php">Send Level</a>
                        <a href="'. $dbPath . '/mod/sentList.php">Sent List</a>
                        <a href="'. $dbPath . '/mod/getUserID.php">Get User ID</a>
                        <a href="'. $dbPath . '/mod/unbanUser.php">Unban User</a>
                    </div>
                </div>';
            }

            if($userInfo['permLevel'] > 1) {
                $headerString .= '<div class="dropdown">
                    <a class="dropbtn"><i class="fa-solid fa-gears"></i>Admin</a>
                    <div class="dropdown-content">
                        <a href="'. $dbPath . '/admin/forceChangeUsername.php">Force Change Username</a>
                        <a href="'. $dbPath . '/admin/forceChangePassword.php">Force Change Password</a>
                        <a href="'. $dbPath . '/admin/rateLevel.php">Rate Level</a>
                    </div>
                </div>';
            }

            $headerString .= '<div class="dropdown">
            <a class="dropbtn"><i class="fas fa-chart-simple"></i>Stats</a>
            <div class="dropdown-content">
                <a href="'. $dbPath . '/stats/reuploadsTable.php">Uploaded Songs</a>
                <a href="'. $dbPath . '/stats/leaderboard.php">Stars Leaderboard</a>
                <a href="'. $dbPath . '/stats/topCreators.php">Creators Leaderboard</a>
                <a href="'. $dbPath . '/stats/topDemons.php">Demons Leaderboard</a>
                <a href="'. $dbPath . '/stats/detailedStats.php">Detailed Stats</a>
                <a href="'. $dbPath . '/stats/mappacks.php">Map Packs</a>
            </div>
            </div>';
    
            $headerString .= '</div>
            <div class="right-menu">
                <a href="'. $dbPath . '/auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
            </div>
            </div>';
        } else {
            $headerString .= '<div class="dropdown">
            <a class="dropbtn"><i class="fas fa-chart-simple"></i>Stats</a>
            <div class="dropdown-content">
                <a href="'. $dbPath . '/stats/reuploadsTable.php">Uploaded Songs</a>
                <a href="'. $dbPath . '/stats/leaderboard.php">Stars Leaderboard</a>
                <a href="'. $dbPath . '/stats/topCreators.php">Creators Leaderboard</a>
                <a href="'. $dbPath . '/stats/topDemons.php">Demons Leaderboard</a>
                <a href="'. $dbPath . '/stats/detailedStats.php">Detailed Stats</a>
                <a href="'. $dbPath . '/stats/mappacks.php">Map Packs</a>
            </div>
            </div>';

            $headerString .= '</div>
            <div class="right-menu">
                <a href="'. $dbPath . '/auth/login.php"><i class="fa-solid fa-right-to-bracket"></i>Login</a>
            </div>
        </div>';
        }
    
        echo($headerString);
    }

    public function printStyle() {
        echo('<style>'. file_get_contents(__DIR__ . '/../style/dashboard.css') . '</style>');
    }

    public function printLoginBox() {
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">Login to account</div>
            <form method="POST" action="login.php">
            <input type="text" id="username" name="username" placeholder="Username" class="input">
            <input type="password" id="password" name="password" placeholder="Password" class="input">
            <input type="submit" value="Login" class="input">
            </form>
        </div>';
    }

    public function printMessageBox($title, $message) {
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">'.$title.'</div>'. $message . '<button onclick="window.location.replace(location.pathname);">Try again</button>
        </div>';
    }

    public function printStatsInfo() {
        include __DIR__ . "/connection.php";

        global $dbPath;

        # users count

        $sql = $conn->prepare("SELECT COUNT(*) FROM users");
        $sql->execute();
        $users = $sql->fetchColumn();

        # levels count

        $sql = $conn->prepare("SELECT COUNT(*) FROM levels");
        $sql->execute();
        $levels = $sql->fetchColumn();

        # rated levels count

        $sql = $conn->prepare("SELECT COUNT(*) FROM levels WHERE stars > 0");
        $sql->execute();
        $starLevels = $sql->fetchColumn();

        # featured levels count

        $sql = $conn->prepare("SELECT COUNT(*) FROM levels WHERE featureScore > 0");
        $sql->execute();
        $featuredLevels = $sql->fetchColumn();

        # comments count

        $sql = $conn->prepare("SELECT COUNT(*) FROM comments");
        $sql->execute();
        $comments = $sql->fetchColumn();

        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">Detailed Stats</div>
            <div class="text">Users: '. $users .'<br>Levels: '. $levels . '<br>Rated levels: '.$starLevels.'<br>Comments: '.$comments.'<br>Featured levels: '.$featuredLevels.'</div>
        </div>
    </div>';
    }

    public function printTitleBox($title) {
        echo("<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>". $title ."</div>
        </div>
    </div>");
    }

    public function printMessageBox2($title, $text) {
        echo("<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>". $title ."</div>
            <div class='text'>".$text."</div>
        </div>
    </div>");
    }

    public function printQuickActions() {

        global $dbPath;
        global $windowsDL;
        global $androidDL;
        global $iosDL;
        global $serverName;

        echo "<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>Welcome to $serverName</div>
            <div class='text'>Welcome to $serverName! Here you can browse through the server, manage your account, and more! Below are some shortcuts for common things to do here.</div>
            <div class='quick-actions'>
                <li><a href='" .$dbPath . "/user/uploadSong.php'.''>Upload Song</a></li>
                <li><a href='". $dbPath . "/stats/reuploadsTable.php'.''>Reuploaded Songs List</a></li>
                <li><a href='" . $dbPath ."/stats/leaderboard.php'.''>Stars Leaderboard</a></li>
                <li><a href='" . $dbPath . "/stats/detailedStats.php'.''>Detailed Stats</a></li>
                <li><a href='" . $dbPath."/stats/mappacks.php'.''>Map Packs List</a></li>
            </div>
            <div class='title'>Download the server</div>
            <div class='text'>In case you haven't downloaded the server yet, there are downloads below for the platforms the GDPS is available on.</div>
            <div class='quick-actions'>
                <li><a href='$windowsDL'>Windows Download</a></li>
                <li><a href='$androidDL'>Android Download</a></li>
                <li><a href='$iosDL'>IOS Download</a></li>
            </div>
        </div>";
    }

    public function checkLoginStatus() {
        if(isset($_SESSION['username'], $_SESSION['password'])) {
            return 1;
        } else {
            return 0;
        }
    }

    public function printUserActions() {

        global $dbPath;

        echo "<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>User Actions</div>
            <div class='text'>Below are a list of actions you can preform as a logged in user.
                <div class='quick-actions'>
                <li><a href='".$dbPath . "/user/uploadSong.php"."'>Upload Song</a></li>
                <li><a href='".$dbPath . "/user/changeUsername.php"."'>Change Username</a></li>
                <li><a href='".$dbPath . "/user/changePassword.php"."'>Change Password</a></li>
                <li><a href='".$dbPath . "/user/changeEmail.php"."'>Change Email</a></li>
                </div>
            </div>
        </div>
    </div>";
    }

    public function printEmailChange() {
        echo "<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>Change Email</div>
            <form method='POST' action='changeEmail.php'>
            <input type='email' id='newEmail' name='newEmail' placeholder='New Email' class='input'>
            <input type='submit' value='Change Email' class='input'>
            </form>
        </div>
    </div>";
    }

    public function printMessageBox3($title, $message) {
        global $dbPath;
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">'.$title.'</div>'. $message . '<button onclick="window.location.replace(\'' . $dbPath . '\');">Home</button>
        </div>
    </div>';
    }

    public function printMessageBox4($title, $message, $url, $buttonText) {
        global $dbPath;
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">'.$title.'</div>'. $message . '<button onclick="window.location.replace(\'' . $url . '\');">'. $buttonText .'</button>
        </div>
    </div>';
    }

    public function printUsernameChange() {
        echo "<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>Change Username</div>
            <form method='POST' action='changeUsername.php'>
            <input type='text' id='userName' name='newUsername' placeholder='New Username' class='input'>
            <input type='submit' value='Change Username' class='input'>
            </form>
        </div>
    </div>";
    }

    public function printPasswordChange() {
        echo "<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>Change Password</div>
            <form method='POST' action='changePassword.php'>
            <input type='password' id='password' name='newPassword' placeholder='New Password' class='input'>
            <input type='submit' value='Change Password' class='input'>
            </form>
        </div>
    </div>";
    }

    public function printStatsPage() {
        
        global $dbPath;

        echo "<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>Server Stats</div>
            <div class='text'>Here are some pages related to server stats.
                <div class='quick-actions'>
                <li><a href='".$dbPath . "/stats/reuploadsTable.php"."'>Reuploaded Songs</a></li>
                <li><a href='".$dbPath . "/stats/leaderboard.php"."'>Stars Leaderboard</a></li>
                <li><a href='".$dbPath . "/stats/topCreators.php"."'>Creators Leaderboard</a></li>
                <li><a href='".$dbPath . "/stats/topDemons.php"."'>Demons Leaderboard</a></li>
                <li><a href='".$dbPath . "/stats/detailedStats.php"."'>Detailed Stats</a></li>
                <li><a href='".$dbPath . "/stats/mappacks.php"."'>Map Packs</a></li>
                </div>
            </div>
        </div>
    </div>";
    }

    public function checkPermsLevel() {

        include_once __DIR__ . "/mainLib.php";

        $ml = new mainLib();
        
        if($this->checkLoginStatus() != 1) {
            return -1;
        } else {
            $accID = $ml->getAccountID($_SESSION['username']);
            $udid = $ml->getUDIDFromAccountID($accID);
            $userID = $ml->getUserID($udid);
            $userInfo = $ml->getUserStats($userID);

            return $userInfo['permLevel'];
        }
    }

    public function printSongReupForm() {
            echo "<div class='center-dialog-container'>
            <div class='center-dialog'>
                <div class='title'>Upload Song</div>
                <form method='POST' action='uploadSong.php' enctype='multipart/form-data'>
                <input type='file' name='songFile' id='songFile' accept='.mp3' placeholder='Song File' required>
                <input type='text' name='songName' maxlength='30' id='songName' placeholder='Song Name' required>
                <input type='text' name='songAuthor' maxlength='15' id='songAuthor' placeholder='Song Author' required>
                <input type='submit' value='Upload Song' class='input'>
                </form>
            </div>
        </div>";
    }

    public function printModActions() {

        global $dbPath;

        echo "<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>Mod Actions</div>
            <div class='text'>Here are the actions you can preform as a moderator.
                <div class='quick-actions'>
                <li><a href='".$dbPath . "/mod/banUser.php"."'>Ban User</a></li>
                <li><a href='".$dbPath . "/mod/bansList.php"."'>Bans List</a></li>
                <li><a href='".$dbPath . "/mod/reportsList.php"."'>Reported Levels</a></li>
                <li><a href='".$dbPath . "/mod/sendLevel.php"."'>Send Level</a></li>
                <li><a href='".$dbPath . "/mod/sentList.php"."'>Sent List</a></li>
                <li><a href='".$dbPath . "/mod/getUserID.php"."'>Get User ID</a></li>
                <li><a href='".$dbPath . "/mod/unbanUser.php"."'>Unban User</a></li>
                </div>
            </div>
        </div>
    </div>";
    }

    public function printBanUserForm() {
        echo("<div class='center-dialog-container'>
        <div class='center-dialog'>
        <div class='title'>Ban User</div>
        <div class='text'>Here you can ban a user. To get user ID use the tools on the moderation main page.</div>
        <form action='banUser.php' method='POST'>
        <select id='banType' name='banType' placeholder='Ban Type' required>
            <option value='uploading'>Uploading Levels</option>
            <option value='commenting'>Commenting</option>
            <option value='creatorsLB'>Top Creators</option>
            <option value='playersLB'>Top Players</option>
        </select>
        <input type='number' name='targetID' id='targetID' placeholder='Target User' required>
        <input type='number' name='expires' id='expires' placeholder='Expires (0 for never)' required>
        <input type='text' name='reason' id='reason' placeholder='Reason' required>
        <input type='submit' id='submit' value='Ban user'>
    </form>
    </div>
    </div>");
    }

    public function printMessageBox5($title, $message) {
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">'.$title.'</div>'. $message . '<button onclick="window.location.replace(location.pathname);">Another Level</button>
        </div>';
    }

    public function printSendForm() {
        echo "<div class='center-dialog-container'>
        <div class='center-dialog'>
            <div class='title'>Send Level</div>
            <form action='sendLevel.php' method='POST'>
                <input type='number' name='levelID' id='levelID' min=0 required class='input' placeholder='Level ID'>
                <br>
                <input type='number' name='stars' id='stars' min=0 max=10 required class='input' placeholder='Stars'>
                <br>
                <input type='number' name='feature' id='feature' min=0 max=1 required class='input' placeholder='Featured (1 for feature, 0 for rate)'>
                <br>
                <input type='submit' value='Send Level' class='input'>
            </form>
        </div>
    </div>";
    }

    public function printUserIDFindForm() {
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
                <div class="title">UserID Search</div>
                <div class="text">You can use this tool to find userID based on username. Because of the nature of 1.9 multiple users could have the same username. If multiple entries are found, they will be displayed in a table.</div> <br>
                <form action="getUserID.php" method="post">
                    <input type="text" name="searchUsername" placeholder="Username" required>
                    <input type="submit" value="Search">
            </div>
        </div>
    </div>';
    }

    public function printUnbanForm() {
        echo('<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">Unban User</div>
            <div class="text">Here you can unban someone! To get ban ID, use the bans list tool. (tip: you can also unban using that tool)</div> <br>
            <form action="unbanUser.php" method="post">
                <input type="number" name="banID" placeholder="Ban ID" min="0" required>
                <input type="submit" value="Unban">
            </form>
        </div>
    </div>');
    }

    public function printLevelToGMD() {
        echo('<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">Level To GMD</div>
            <div class="text">Here you can download your levels from the server as GMD files!</div><br>
            <form action="levelToGMD.php" method="post">
                <input type="number" name="levelID" placeholder="Level ID" min="0" required>
                <input type="submit" value="Download level">
            </form>
        </div>
    </div>');
    }

    public function printForceChangeUsername() {
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">Force change username</div>
            <div class="text">Here you can force change someone\'s username. They will have to refresh their login ingame after this.</div> <br>
            <form method="POST" action="forceChangeUsername.php">
                <input type="text" id="oldUsername" name="oldUsername" placeholder="Old Username" class="input" required>
                <input type="text" id="newUsername" name="newUsername" placeholder="New Username" class="input" minlength=3 maxlength=20 required>
                <input type="submit" value="Change Username" class="input">
            </form>
        </div>
    </div>';
    }

    public function printForceChangePassword() {
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">Force change password</div>
            <div class="text">Here you can force change someone\'s password. They will have to refresh their login ingame after this.</div> <br>
            <form method="POST" action="forceChangePassword.php">
                <input type="text" id="username" name="username" placeholder="Username" class="input">
                <input type="password" id="newPassword" name="newPassword" placeholder="New Password" minlength=6 maxlength=20 class="input">
                <input type="submit" value="Change Password" class="input">
            </form>
        </div>
    </div>';
    }

    public function printRateForm() {
        echo '<div class="center-dialog-container">
        <div class="center-dialog">
            <div class="title">Rate Level</div>
            <div class="text">Here you can rate a level!</div> <br>
            <form method="POST" action="rateLevel.php">
                <input type="number" id="levelID" name="levelID" placeholder="Level ID" min=1 class="input">
                <input type="number" id="stars" name="stars" placeholder="Star Count" min=1 max=10 class="input">
                <input type="number" id="featuredLevel" name="featuredLevel" placeholder="Featured" min=0 max=1 class="input">
                <input type="submit" value="Rate Level!" class="input">
            </form>
        </div>
    </div>';
    }

}

?>