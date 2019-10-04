<?php
    require_once './includes/connection.php';
    require_once './includes/authentication.php';
    $query = "SELECT * FROM roll_series WHERE roll_no='" . $_SESSION['Username'] . "'";
    $result = $conn->query($query);
    $studentData = $result->fetch_array();
?>
<div class="sidebar" data-color="purple" data-background-color="white" data-image="./assets/img/sidebar-1.jpg">
	<div class="logo">
		<a href="./student-dashboard.php" class="simple-text logo-normal">
			Student  - <?php echo $_SESSION['Username']; ?>
		</a>
	</div>
	<?php
        $navlink = array(
        array('./student-dashboard.php','dashboard','Lectures'),
        array('./student-attendance.php','content_paste','View Attendance'),
		array('./notices.php','notifications_important','Notices'),
		array('./assignment.php','assignment_ind','Assignment'),
        array('./student-timetable.php','library_books','TimeTable')
        );
    ?>
	<div class="sidebar-wrapper">
		<ul class="nav">
			<?php
            	foreach($navlink as $links){
					$active="";
					$hyperlink=explode("/",$links[0]);
					if(end($hyperlink)==explode("?",basename($_SERVER['REQUEST_URI']))[0]) {
						$active="active";
                	}
                	echo '<li class="nav-item '.$active.'">
							<a class="nav-link" href="'.$links[0].'">
								<i class="material-icons">'.$links[1].'</i>
								<p>'.$links[2].'</p>
							</a>
						</li>';
                }
            ?>
		</ul>
	</div>
</div>