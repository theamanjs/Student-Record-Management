<?php

date_default_timezone_set('Asia/Kolkata');

require_once './includes/connection.php';
require_once './includes/authentication.php';
if($_SESSION['userType'] == "lecturer" || $_SESSION['userType'] == "principal" || $_SESSION['userType'] == 'hod'){
    header("Location: dashboard.php");
}

$query = "SELECT * FROM roll_series WHERE roll_no='" . $_SESSION['Username'] . "'";
$result = $conn->query($query);
$studentData = $result->fetch_array();

$date  = date("H:i:s");;

$query = "SELECT * FROM `timings` WHERE CAST('$date' AS time) BETWEEN `time_from` AND `time_to`";
$result = $conn->query($query);
$currentLecture = $result->fetch_array();

?>