<?php
require_once './includes/connection.php';
require_once './includes/authentication.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="./assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Student Timetable - GNDPC</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    
	<!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
    
	<!-- CSS Files -->
    <link rel="stylesheet" href="./assets/main_styles.css">
    <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
    
    <style>
        .table td {
            width: 14.2857%;
        }
    </style>
<?php
include("./loader.php");
?>
</head>

<body class="">
    <script>
        let colorDark = localStorage.getItem("themeColorDark");
        let colorLight = localStorage.getItem("themeColorLight");
        if (typeof colorDark != "undefined" && colorDark != null && colorLight != "undefined" && colorLight != null){
          document.body.style.setProperty('--themeColorDark', colorDark);
          document.body.style.setProperty('--themeColorLight', colorLight);
          document.body.style.setProperty('--themeColorShadow', colorDark+'66');
        }
    </script>
<!-- LOADER -->
<div class="loader-container" style="display: flex">
    <div class="loader-card">
        <div class="loader"></div>
        <div class="loader-text">Loading...</div>
    </div>
</div>
<!-- END OF LOADER -->

	<div class="wrapper ">
        <?php
        require './includes/student-nav.php';
        ?>
		<div class="main-panel">
			<?php
            require './includes/header.php';
            ?>
			<div class="content">
				<div class="container-fluid">
					<div class="card">
						<div class="card-header card-header-primary">
							<h4 class="card-title">Timetable</h4>
						</div>
						<div class="card-body">
							<div class="table-responsive px-5 ">
								<table class="table table-bordered text-center custom_timetable" style="border-top: 1px solid #ddd;">
									<thead class="tableHead">
										<tr>
											<th scope="col">Lectures</th>
											<th scope="col">Monday</th>
											<th scope="col">Tuesday</th>
											<th scope="col">Wednesday</th>
											<th scope="col">Thursday</th>
											<th scope="col">Friday</th>
											<th scope="col">Lectures</th>
										</tr>
									</thead>
									<tbody id="timetable" class="text-center">
                                        <?php
                                        
                                        // To find the section of the student
                                        $query = "SELECT sec FROM departments WHERE initials='" . $studentData['department'] . "'";
                                        $sections = explode(",", $conn->query($query)->fetch_array()[0]); // fetching sections of student's department
                                        
										if(intval(substr($studentData['roll_no'], -2)) <= 50 && intval(substr($studentData['roll_no'], -2)) >= 1) {
											$section = $sections[0]; // assiging 1st section if roll number is less than 50
										} else {
											$section = $sections[1]; // assigning 2nd section if roll number is greater than 50
                                        }
                                        
                                        // To fetch all the subject of the student's class
                                        $query = "SELECT * FROM subjects WHERE semester='" . $studentData['semester'] . "' AND department='" . $studentData['department'] . "'";
                                        $result = $conn->query($query);
                                        $counter = 0;
                                        $subjects = array();
                                        while($row = $result->fetch_array(MYSQLI_NUM)) {
                                            $subjects[$counter] = $row;
                                            $counter++;
                                        }
                                        $timetable = array();

                                        // Creating the array of timetable
                                        $query = "SELECT * FROM " . $studentData['department'] . "_timetable WHERE semester='" . $studentData['semester'] . "' AND section='" . $section . "'";
                                        $result = $conn->query($query);
                                        $counter = 1;
                                        while($row = $result->fetch_array()) {
                                            $timetable[$counter] = array();
                                            for($i = 1; $i <= 8; $i++) {
                                                for($j = 0; $j < count($subjects); $j++) {
                                                    if(strcmp(($subjects[$j][2]), explode(":", $row['lecture_' . $i])[0]) == 0) {
                                                        break;
                                                    }
                                                }
                                                $teachers = "";
                                                foreach(explode(",", explode(":", $row['lecture_' . $i])[1]) as $teacher) {
                                                    $qry = "SELECT * FROM teacher_list WHERE teacher_code='" . $teacher . "'";
                                                    $teachers .= $conn->query($qry)->fetch_array()[3] . ",";
                                                }
                                                $teachers = substr($teachers, 0 , -1);
                                                $timetable[$counter][$i] = array(
                                                    "subject" => $subjects[$j][3],
                                                    "teachers" => $teachers,
                                                    "type" => $subjects[$j][6]);
                                            }
                                            $counter++;
                                        }     

                                        // Printing the array of timetable
                                        for($i = 1; $i <=8; $i++) {
                                            echo "<tr><td>" . $i . "</td>";
                                            for($j = 1; $j <=5; $j++) {
                                                if($timetable[$j][$i]['type'] == "Theory")
                                                    $type = "L";
                                                else
                                                    $type = "P";
                                                echo "
                                                <td><span class='d-inline-block'>" . $type . "</span><span class='d-inline-block ml-2'>" . $timetable[$j][$i]['subject'] ."</span><span class='d-block'>" . $timetable[$j][$i]['teachers'] . "</span></td>
                                                ";
                                            }
                                            echo "<td>" . $i . "</td><tr>";
                                        } 
                                        // echo "<pre>";
                                        // print_r($timetable);
                                        // echo "</pre>";                                  
                                        ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php

  require './includes/footer.php';
    
    ?>
		</div>
    </div>
    
	<!-- JS Files -->
            <script src="./assets/js/core/jquery.min.js"></script>
            <script src="./assets/js/core/popper.min.js"></script>
            <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
            <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
            <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
            <script src="assets/sidebarSript.js"></script>

            <script>
                    $(document).ready(function() {
                      $(".loader-container").css("display","none");
                    });
            </script>
</body>

</html>