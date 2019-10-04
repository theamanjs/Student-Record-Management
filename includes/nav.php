<?php
    require_once './includes/connection.php';
    require_once './includes/authentication.php';
    $query = "SELECT * FROM teacher_list WHERE teacher_username='" . $_SESSION['Username'] . "'";
    $teacherData = $conn->query($query)->fetch_array();
?>
<div class="sidebar" data-color="purple" data-background-color="white" data-image="./assets/img/sidebar-1.jpg">
	<div class="logo">
		<a href="./dashboard.php" class="simple-text logo-normal">
		<?php 
		if($teacherData['designation'] == 'hod')
		echo 'Head of Department';
		else
		echo $teacherData['designation']; ?>
		</a>
	</div>
	<?php
       if($teacherData['designation'] == 'hod'){
        $navlink = array(
        array('./user.php','person',$teacherData['name']),
        array('./dashboard.php','&#xe22b','Fill Attendance'),
        array('./viewattendance.php','content_paste','View Attendance'),
        array('./timetable.php','library_books','TimeTable'),
        array('./notices.php','notifications_important','Notices'),
		array('./assignment.php','assignment_ind','Assignment'),
        array('./adjustments.php','repeat','Adjustments'),
		array('./adjustments-report.php','receipt','Adjustments Report'),
        array('./add.php','playlist_add','Add things'),
        array('./roll-series.php','delete_sweep','Roll series'),
        array('./attendance-report.php','assessment','Report'),
        array('./viewlog.php','assignment','Logs')

		);
	}
	else if($teacherData['designation'] == 'lecturer'){
        $navlink = array(
        array('./user.php','person',$teacherData['name']),
        array('./dashboard.php','&#xe22b','Fill Attendance'),
        array('./viewattendance.php','content_paste','View Attendance'),
        array('./notices.php','notifications_important','Notices'),
		array('./assignment.php','assignment_ind','Assignment'),
        array('./timetable.php','library_books','TimeTable')
        // array('./add.php','playlist_add','Add things'),
        // array('./roll-series.php','delete_sweep','Detain/Retain Student')
		);
	}
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
