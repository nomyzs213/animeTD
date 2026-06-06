<?php
$conn = new mysqli('localhost', 'root', '', 'yurii_animeclicker');
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

?>