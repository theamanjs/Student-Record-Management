<?php
require_once './includes/connection.php';
require_once './includes/authentication.php';
  $searchedRoll = $_POST['searched-roll'];
  $query = "SELECT * FROM roll_series WHERE roll_no='".$_POST['searched-roll']."'";
  $newResult = $conn->query($query);
  if($newResult->num_rows == 0) {
	echo "<script>alert('Student not found!');window.location.href='dashboard.php'</script>";
	// header("Location: dashboard.php");
  }
  $studentData = $newResult->fetch_array();
  if($_SESSION['userType'] == "Student"){
	header("Location: student-dashboard.php");
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="./assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Lectures - GNDPC</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	
	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
	
	<!-- CSS Files -->
	<link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />

	<style type="text/css">
		.card {
			cursor: default;
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
	
<div class="modal" tabindex="-1" role="dialog" id="modalPractical">
  <div class="modal-dialog" style="max-width:700px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Practical Lectures Chart</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <canvas id="canvasPractical"></canvas>
      </div>
    </div>
  </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="modalTheory">
  <div class="modal-dialog" style="max-width:700px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Theory Lectures Chart</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <canvas id="canvasTheory"></canvas>
      </div>
    </div>
  </div>
</div>

	<?php
	require './includes/nav.php';
	?>
	<div class="wrapper ">
		<div class="main-panel">
			<?php
  			require_once './includes/header.php';
		    ?>
			<div class="content">
				<div class="container-fluid">
				    <h4>Showing record of Roll number- <?php echo $searchedRoll ?></h4>
					<div class="row">

						<!-- Total Lecture -->
                                    <div class="col-lg col-md-3 col-xs-6 col-sm-6">
                                        <div class="card">
                                            <div class="card-header card-header-primary">
                                                <h4 class="card-title text-center">Total Lectures</h4>
                                            </div>
                                            <div class="card-body py-4">
                                                <h1 class="text-center">
										<?php
    										$lectures = (object)(json_decode($studentData['attendance']));
    										echo $lectures->totalLectures;
    										?>
    									</h1>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attended Lectures -->
                                    <div class="col-lg col-md-3 col-xs-6 col-sm-6">
                                        <div class="card">
                                            <div class="card-header card-header-primary">
                                                <h4 class="card-title text-center">Attended Lectures</h4>
                                            </div>
                                            <div class="card-body py-4">
                                                <h1 class="text-center">
										<?php
    										echo $lectures->lectureCount;
    										?>
    									</h1>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Percentage -->
                                    <div class="col-lg col-md-3 col-xs-6 col-sm-6">
                                        <div class="card">
                                            <div class="card-header card-header-primary">
                                                <h4 class="card-title text-center pr-3">Percentage</h4>
                                            </div>
                                            <div class="card-body py-4">
                                                <h1 class="text-center">
										<?php
    										echo round(intval($lectures->lectureCount) / intval($lectures->totalLectures) * 100) . "%";
    										?>
    									</h1>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                						if((intval($lectures->lectureCount) / intval($lectures->totalLectures) * 100) < 75) {
                							$minLectures = 75 / 100 * intval($lectures->totalLectures);
                							$shortLectures = round($minLectures - $lectures->lectureCount);
                							echo "<div class='col-lg col-md-3 col-xs-6 col-sm-6'>
                									<div class='card'>
                										<div class='card-header card-header-primary'>
                											<h4 class='card-title text-center'>Short Lectures</h4>
                										</div>
                										<div class='card-body py-4'>
                											<h1 class='text-center'>".$shortLectures."</h1>
                										</div>
                									</div>
                								</div>";
                						}
                						?>
                                    </div>
                            </div>
                            <div class="my-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between card-header-primary">
                                        <h4 class="card-title mt-2"> Practicals </h4>
                                        <button class="btn btn-primary m-0 view-chart-button" data-toggle="modal" data-target="#modalPractical">View Chart</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table text-center">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Total Lectures</th>
                                                        <th>Attended Lectures</th>
                                                        <th>Percentage</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                    $practicalSubjects = array();
                                                    $theorySubjects = array();
            										$query = "SELECT sec FROM departments WHERE initials='" . $studentData['department'] . "'";
            										$sections = explode(",", $conn->query($query)->fetch_array()[0]);
            										if(intval(substr($studentData['roll_no'], -2)) <= 50 && intval(substr($studentData['roll_no'], -2)) >= 1) {
            											$section = $sections[0];
            										} else {
            											$section = $sections[1];
            										}
            										$query = "SELECT * FROM subjects WHERE department='" . $studentData['department'] . "' AND semester='" . $studentData['semester'] . "' ORDER BY lecture_type, subject_name";
            										$result = $conn->query($query);
            										$subjects = array();
            										while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            											array_push($subjects, $row);
            										}
            										$attendance = (object)json_decode($studentData['attendance']);
            										$lectures = array();
                                                    $counter = 0;
            										foreach($subjects as $key => $subject) {
            											if($subject['lecture_type'] == "Practical") {
            												$totalLectures = (object)(json_decode($subject['total_lectures']));
            												$totalLectures = isset($totalLectures->{$section}) ? $totalLectures->{$section} : 0;
            												if($totalLectures == 0) {
            													$percentage = 0;
            												} else {
            													$percentage = round(intval($attendance->{$subject['subject_code']}) / intval($totalLectures) * 100);
            												}
            												if(isset($attendance->{$subject['subject_code']})) {
            													echo "<tr>
            													<td>" . $subject['subject_initials'] . "</td>
            													<td>" . $totalLectures . "</td>
            													<td>" . $attendance->{$subject['subject_code']} . "</td>
            													<td>" . $percentage . "%</td>
            													</tr>";
                                                                $practicalSubjects[$counter]['attendedLectures'] = $attendance->{$subject['subject_code']};
            												} else {
            													echo "<tr>
            													<td>" . $subject['subject_initials'] . "</td>
            													<td>" . $totalLectures . "</td>
            													<td>0</td>
            													<td>0%</td>
            													</tr>";
                                                                $practicalSubjects[$counter]['attendedLectures'] = 0;
            												}
                                                            $practicalSubjects[$counter]['subject'] = $subject['subject_initials'];
                                                            $practicalSubjects[$counter]['totalLectures'] = $totalLectures;
                                                            $counter++;
            											}
            										}
            										?>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="my-4 pt-3">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between card-header-primary">
                                        <h4 class="card-title mt-2"> Theory </h4>
                                        <button class="btn btn-primary m-0 view-chart-button" data-toggle="modal" data-target="#modalTheory">View Chart</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table text-center">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Total Lectures</th>
                                                        <th>Attended Lectures</th>
                                                        <th>Percentage</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                $counter = 0;
            										foreach($subjects as $key => $subject) {
            											if($subject['lecture_type'] == "Theory") {
            												$totalLectures = (object)(json_decode($subject['total_lectures']));
            												$totalLectures = isset($totalLectures->{$section}) ? $totalLectures->{$section} : 0;
            												if(isset($attendance->{$subject['subject_code']})) {
            													if($totalLectures == 0) {
            														$percentage = 0;
            													} else {
            														$percentage = round(intval($attendance->{$subject['subject_code']}) / intval($totalLectures) * 100);
            													}
            													echo "<tr>
            													<td>" . $subject['subject_initials'] . "</td>
            													<td>" . $totalLectures . "</td>
            													<td>" . $attendance->{$subject['subject_code']} . "</td>
            													<td>" . $percentage . "%</td>
            													</tr>";
                                                                $theorySubjects[$counter]['attendedLectures'] =  $attendance->{$subject['subject_code']};
            												} else {
            													echo "<tr>
            													<td>" . $subject['subject_initials'] . "</td>
            													<td>" . $totalLectures . "</td>
            													<td>0</td>
            													<td>0%</td>
            													</tr>";
                                                                $theorySubjects[$counter]['attendedLectures'] =  0;
            												}
                                                        $theorySubjects[$counter]['subject'] =  $subject['subject_initials'];
                                                        $theorySubjects[$counter]['totalLectures'] =  $totalLectures;
                                                        $counter++;
            											}
            										}
            										?>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
			<?php
				// </div>
                require './includes/footer.php';
                // print_r($studentData);
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
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
		<script>
				$(document).ready(function() {
					$(".loader-container").css("display","none");
				});
		</script>
		<script>
	var configPractical = {
		type: 'line',
		data: {
			labels: [<?php
					$temp = "";
					foreach($practicalSubjects as $subject) {
						$temp .=  "'" . $subject['subject'] . " (P)',";
					}
					echo substr($temp, 0, -1);
			?>],
			datasets: [{
				label: 'Total Lectures',
				fill: false,
				backgroundColor: "#ED5D7C",
				borderColor: "#FF2D39",
				data: [<?php
					$temp = "";
					foreach($practicalSubjects as $subject) {
						$temp .=  "'" . $subject['totalLectures'] . "',";
					}
					echo substr($temp, 0, -1);
			?>],
			}, {
				label: 'Attended Lectures',
				fill: true,
				backgroundColor: "#A0CDD9",
				borderColor: "#3298DB",
				data: [<?php
					$temp = "";
					foreach($practicalSubjects as $subject) {
						$temp .=  "'" . $subject['attendedLectures'] . "',";
					}
					echo substr($temp, 0, -1);
			?>],
			}]
		},
		options: {
			responsive: true,
			/*title: {
				display: true,
				text: 'Chart.js Line Chart'
			},*/
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Subjects'
					}
				}],
				yAxes: [{
					display: true,
					ticks: {
						min: 0,
						stepSize: 1
					},
					scaleLabel: {
						display: true,
						labelString: 'Lectures Count'
					}
				}]
			}
		}
	};
	var configTheory = {
		type: 'line',
		data: {
			labels: [<?php
					$temp = "";
					foreach($theorySubjects as $subject) {
						$temp .=  "'" . $subject['subject'] . " (T)',";
					}
					echo substr($temp, 0, -1);
			?>],
			datasets: [{
				label: 'Total Lectures',
				fill: false,
				backgroundColor: "#ED5D7C",
				borderColor: "#FF2D39",
				data: [<?php
					$temp = "";
					foreach($theorySubjects as $subject) {
						$temp .=  "'" . $subject['totalLectures'] . "',";
					}
					echo substr($temp, 0, -1);
			?>],
			}, {
				label: 'Attended Lectures',
				fill: true,
				backgroundColor: "#A0CDD9",
				borderColor: "#3298DB",
				data: [<?php
					$temp = "";
					foreach($theorySubjects as $subject) {
						$temp .=  "'" . $subject['attendedLectures'] . "',";
					}
					echo substr($temp, 0, -1);
			?>],
			}]
		},
		options: {
			responsive: true,
			/*title: {
				display: true,
				text: 'Chart.js Line Chart'
			},*/
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Subjects'
					}
				}],
				yAxes: [{
					display: true,
					ticks: {
						min: 0,
						stepSize: 1
					},
					scaleLabel: {
						display: true,
						labelString: 'Lectures Count'
					}
				}]
			}
		}
	};
	window.onload = function() {
		var ctxTheory = document.getElementById('canvasTheory').getContext('2d');
		var ctxPractical = document.getElementById('canvasPractical').getContext('2d');
		window.myLineTheory = new Chart(ctxTheory, configTheory);
		window.myLinePractical = new Chart(ctxPractical, configPractical);
	};
	</script>
</body>
</html>
