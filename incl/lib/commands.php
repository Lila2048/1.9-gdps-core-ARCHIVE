<?php
class commands {
    public function ProcessCommand($comment, $udid, $levelID) {
        include_once __DIR__ . "/mainLib.php";
        $ml = new mainLib();
        $commentArray = explode(' ', $comment);

        # rate command

        if(substr($comment, 0 , 5) == "!rate") {
            $stars = $commentArray[1];
            if(isset($commentArray[2])) {
                $featured = $commentArray[2];
            } else {
                $featured = 0;
            }

            $permsCheck = $ml->checkPerms(2, $udid);

            if($permsCheck == 1) {
                $ml->rateLevel($levelID, $stars, $featured);
                echo(-1);
                die();
            } else {
                echo(-1);
                die();
            }
            
        }

        if(substr($comment, 0 , 7) == "!unrate") {
            
            $permsCheck = $ml->checkPerms(2, $udid);

            if($permsCheck == 1) {
                $ml->unrateLevel($levelID);
                echo(-1);
                die();
            } else {
                echo(-1);
                die();
            }
            
        }

        if(substr($comment, 0 , 8) == "!feature") {
            
            $permsCheck = $ml->checkPerms(2, $udid);

            if($permsCheck == 1) {
                $ml->featureLevel($levelID);
                echo(-1);
                die();
            } else {
                echo(-1);
                die();
            }
            
        }

        if(substr($comment, 0 , 10) == "!unfeature") {
            
            $permsCheck = $ml->checkPerms(2, $udid);

            if($permsCheck == 1) {
                $ml->unfeatureLevel($levelID);
                echo(-1);
                die();
            } else {
                echo(-1);
                die();
            }
            
        }

        
        if(substr($comment, 0 , 7) == "!delete") {
            
            $permsCheck = $ml->checkPerms(2, $udid);

            if($permsCheck == 1) {
                $ml->deleteLevel($levelID);
                echo(-1);
                die();
            } else {
                echo(-1);
                die();
            }
            
        }

        if(substr($comment, 0 , 5) == "!diff") {

            $diff = $commentArray[1];
            
            $permsCheck = $ml->checkPerms(2, $udid);

            if($permsCheck == 1) {
                $ml->setLevelDiff($levelID, $diff);
                echo(-1);
                die();
            } else {
                echo(-1);
                die();
            }
            
        }

        if(substr($comment, 0 , 4) == "!req") {

            $stars = $commentArray[1];
            $feature = $commentArray[2];
            
            $permsCheck = $ml->checkPerms(1, $udid);

            if($permsCheck == 1) {
                $ml->sendLevel($levelID, $stars, $feature, $udid);

                echo(-1);
                die();
            } else {
                echo(-1);
                die();
            }
            
        }
    }
}
?>