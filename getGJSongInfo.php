<?php

    include __DIR__ . "/incl/lib/connection.php";
    include __DIR__ . "/incl/lib/mainLib.php";
    include __DIR__ . "/incl/lib/exploitPatch.php";

    $ml = new MainLib();

    # get data

    $secret = exploitPatch::clean($_POST['secret']);
    $songID = exploitPatch::clean($_POST['songID']);

    # secret check

    if($secret != "Wmfd2893gb7") {
        die("-1");
    }

    # fetch song

    $song = $ml->getSongInfo($songID);
    echo($song);

?>