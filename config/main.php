<?php

$ratedLevelDeletes = false; # false: users can't delete their own levels if they are rated. true: users can delete their own levels even if they are rated

# Rate limits

$songReupTime = 60; # cooldown in seconds for song reupload, default 60
$levelUploadTime = 60; # cooldown in seconds for level upload, default 60

# the next 3 are currently unused and will be implemented later on

$usernameChangeTime = 604800; # cooldown in seconds for username change, default 604800 (7 days)
$passwordChangeTime = 0; # cooldown in seconds for password changes, default 0
$emailChangeTime = 604800; # cooldown in seconds for email changes, default 604800 (7 days)

# diff faces url

$diffFacesUrl = "https://gcs.icu/WTFIcons/difficulties/";

$publicSentList = false; # if the sent list on the dashboard should be public. false = mod only true = everyone (default false) (not working yet)
?>