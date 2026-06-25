<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "logbook_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!--© 2026 Florence Pearl B. Tonsay. All rights reserved.-->