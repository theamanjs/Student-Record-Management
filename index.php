<?php
session_start();
if(isset($_SESSION['userActive'])) {
    if($_SESSION['userType'] == "lecturer" || $_SESSION['userType'] == "hod") {
        header("Location:dashboard.php");
    } else if($_SESSION['userType'] == "Student") {
        header("Location: student-dashboard.php");
    }
} else {
    header("Location: login.php");
}
?>