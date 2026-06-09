<?php
$conn = new mysqli('localhost', 'root', '', 'clicker');
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

?>