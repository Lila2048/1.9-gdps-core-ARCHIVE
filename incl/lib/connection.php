<?php
include __DIR__ . "/../../config/connection.php";

try {
$conn = new PDO("mysql:host=$sql_servername;dbname=$sql_dbname", $sql_username, $sql_password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
echo "Connection failed: " . $e->getMessage();
}
?>