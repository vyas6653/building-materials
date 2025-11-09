<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "building_materials"; // same as your MySQL DB name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>