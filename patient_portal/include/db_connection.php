<?php
$servername = "localhost";
$username = "root";
$password = "We@kdays1979";
$dbname = "patient_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
