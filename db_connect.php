<?php

// Database connection
$servername = "d123173.mysql.zonevs.eu";
$username = "d123173_maksdot";
$password = "Tark123456";
$dbname = "d123173_calendar";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}