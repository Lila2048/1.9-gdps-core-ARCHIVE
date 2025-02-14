<?php

    include __DIR__ . "/incl/lib/connection.php";
    include __DIR__ . "/incl/lib/mainLib.php";

    $ml = new MainLib();

    # getting data

    $type = $_POST['type'];
    $udid = $_POST['udid'];
    $accountID = $_POST['accountID'];
    $count = $_POST['count'];
    $secret = $_POST['secret'];

    $bansCsv = $ml->getCommaSeparatedBans(5);

    $index = 0;
    $userString = "";

    if($secret != "Wmfd2893gb7") {
        die("-1");
    }

    if($type == "top") {

        $queryString = "SELECT * FROM users WHERE stars > 0 AND userID";

        if($bansCsv != "") {
            $queryString .= " NOT IN ($bansCsv)";
        }

        # top 100

        $sql = $conn->prepare($queryString . " ORDER BY stars DESC LIMIT 100");
        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $user) {
            $index++;
            $userString .= "1:" . $user['userName'] . ":2:" . $user['userID'] . ":3:" . $user['stars'] . ":4:" . $user['demons'] . ":6:". $index . ":7:" . $user['accountID'] . ":8:" . $user['creatorPoints'] . ":9:" . $user['icon'] . ":10:" . $user['color1'] . ":11:" . $user['color2'] . ":13:" . $user['coins'] . ":14:" . $user['iconType']. ":15:" . $user['special'] . ":16:" . $user['accountID'] . "|";
        }

        $userString = rtrim($userString, "|");

        echo($userString);

    }

    if($type == "relative") {

        # check if user is banned

        $userID = $ml->getUserID($udid);

        $banState = $ml->checkBanState($userID, 5);

        if($banState == 1) {
            die("-1");
        }

        # query execution

        $sql = $conn->prepare("SELECT stars FROM users WHERE udid = :udid");
        $sql->execute([':udid' => $udid]);

        $stars = $sql->fetchColumn();

        $sql = $conn->prepare("(SELECT * FROM users WHERE stars <= $stars ORDER BY stars DESC LIMIT 50) UNION (SELECT * FROM users WHERE stars >= $stars ORDER BY stars ASC LIMIT 50)");

        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $user) {
            $index++;
            $userString .= "1:" . $user['userName'] . ":2:" . $user['userID'] . ":3:" . $user['stars'] . ":4:" . $user['demons'] . ":6:". $index . ":7:" . $user['accountID'] . ":8:" . $user['creatorPoints'] . ":9:" . $user['icon'] . ":10:" . $user['color1'] . ":11:" . $user['color2'] . ":13:" . $user['coins'] . ":14:" . $user['iconType']. ":15:" . $user['special'] . ":16:" . $user['accountID'] . "|";
        }

        $userString = rtrim($userString, "|");

        echo($userString);
    }
?>