<?php
$conn = new mysqli('localhost', 'root', '', 'gndpc_attendance');

$query = "UPDATE roll_series SET attendance=''";
$conn->query($query);

$query = "UPDATE subjects SET total_lectures=''";
$conn->query($query);

$query = "DELETE FROM cse";
$conn->query($query);
?>