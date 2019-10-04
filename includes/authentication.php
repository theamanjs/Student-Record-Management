<?php
session_start();
if(!isset($_SESSION['userActive'])) {
    header("Location: login.php");
}
?>