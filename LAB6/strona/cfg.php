<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'moja_strona'; 

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}
$conn->set_charset("utf8");

?>