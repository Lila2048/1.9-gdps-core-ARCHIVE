<?php

class cron {
    public static function refreshSongs() {
        include __DIR__ . "/connection.php";
        $sql = $conn->prepare("DELETE FROM songs WHERE isReupload = 0 AND isBanned = 0");
        $sql->execute();
        return 1;
    }
}

?>