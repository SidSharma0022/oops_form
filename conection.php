<?php
$hostname = "localhost";
$password = ""; 
$username = "root";
$database = "form_oops";

$con = mysqli_connect($hostname, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
