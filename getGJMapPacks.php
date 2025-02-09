<?php

include __DIR__ . "/incl/lib/connection.php";

# gathering info

$page = $_POST['page'];
$secret = $_POST['secret'];

$page2 = $page * 10;
$pageVisual = $page + 10;

$packString = "";

# secret check

if($secret != "Wmfd2893gb7") {
    die(-1);
}

# actual code

$sql = $conn->prepare("SELECT * FROM mappacks ORDER BY difficulty LIMIT 10 OFFSET $page2");
$sql->execute();

$result = $sql->fetchAll(PDO::FETCH_ASSOC);

$packString = "";
foreach($result as $pack) {
    $packString .= "1:" . $pack['packID'] . ":2:" . $pack['packName'] . ":3:" . $pack['levels'] . ":4:" . $pack['stars'] . ":5:" . $pack['coins'] . ":6:" . $pack['difficulty'] . ":7:" . $pack['textColor'] . ":8:" . $pack['barColor'] . "|";
}

$query = $conn->prepare("SELECT count(*) FROM mappacks");
$query->execute();
$packCount = $query->fetchColumn();


echo $packString;
echo "#" . $packCount . ":" . $page2 . ":10";

?>