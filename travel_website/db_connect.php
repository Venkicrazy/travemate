<?php
$conn = mysqli_connect('localhost', 'root', '', 'travelmate');

if (!$conn) {
    die("DB Connection failed: " . mysqli_connect_error());
}
?>
