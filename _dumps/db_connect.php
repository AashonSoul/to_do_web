<?php
$servername = "127.0.0.1:3306";
$username = "root";
$password = 'ACHARYA';
$database = 'to_do_web_db';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
?>