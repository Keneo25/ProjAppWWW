<?php
$login = "admin";
$pass = "admin123";

$dbhost = 'localhost';
$dbuser = 'root';   
$dbpass = '';
$baza = 'moja_strona';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_close($conn);
?>