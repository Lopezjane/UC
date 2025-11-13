<?php
$conn = mysqli_connect('localhost', 'root', '', 'UC');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
