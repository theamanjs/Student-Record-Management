<?php
date_default_timezone_set('Asia/Kolkata');
require_once './includes/connection.php';
require_once './includes/authentication.php';
if($_SESSION['userType'] == "lecturer" || $_SESSION['userType'] == "principal" || $_SESSION['userType'] == 'hod'){
    header("Location: dashboard.php");
}

$date  = date("H:i:s");;

$query = "SELECT * FROM `timings` WHERE CAST('$date' AS time) BETWEEN `time_from` AND `time_to`";
$result = $conn->query($query);
$currentLecture = $result->fetch_array();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Student Dashboard - GNDPC</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />

    <!-- CSS Files -->
    <link rel="stylesheet" href="./assets/main_styles.css">
    <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
    <?php
include("./loader.php");
?>
    <style>
    .custom-chart-button:hover {
        background-color: var(--themeColorLight) !important;
    }
    </style>
</head>

<body class="">

    <script>
    let colorDark = localStorage.getItem("themeColorDark");
    let colorLight = localStorage.getItem("themeColorLight");
    if (typeof colorDark != "undefined" && colorDark != null && colorLight != "undefined" && colorLight != null) {
        document.body.style.setProperty('--themeColorDark', colorDark);
        document.body.style.setProperty('--themeColorLight', colorLight);
        document.body.style.setProperty('--themeColorShadow', colorDark + '66');
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



    <?php
        	require './includes/student-nav.php';
        	?>
    <div class="wrapper ">
        <div class="main-panel">
            <?php
              			require_once './includes/header.php';
            		    ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">

                        <!-- Total Lecture -->
                        <div class="col-lg col-md-3 col-xs-6 col-sm-6">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title text-center">Current Class</h4>
                                </div>
                                <div class="card-body py-4">
                                    <h1 class="text-center">
                                        <?php
											$semester = $studentData['semester'];
											$rollNumber = $studentData['roll_no'];
											$day = date('l');
											$query = "SELECT sec FROM departments WHERE initials='" . $studentData['department'] . "'";
											$sections = explode(",", $conn->query($query)->fetch_array()[0]);
											if(intval(substr($rollNumber, -2)) <= 50 && intval(substr($rollNumber, -2)) >= 1) {
												$section = $sections[0];
											} else {
												$section = $sections[1];
											}
											
											$lecture = $currentLecture['lecture'];
											if($lecture == 0) {
												echo '-';
											} else {
												$query = "SELECT * FROM ".$studentData['department']."_classes WHERE lecture=$lecture AND semester=$semester AND section='$section' AND day='".strtolower($day)."'";
												$result = $conn->query($query);
												$room = $result->fetch_array()['classroom'];
												echo strtoupper(substr($room, 0, 1)) . substr($room, 1);
											}
    										?>
                                    </h1>
                                </div>
                            </div>
                        </div>

                        <!-- Attended Lectures -->
                        <div class="col-lg col-md-3 col-xs-6 col-sm-6">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title text-center">Current Teacher</h4>
                                </div>
                                <div class="card-body py-4">
                                    <h1 class="text-center">
                                        <?php
											if($lecture == 0) {
												echo '-';
											} else {
												$query = "SELECT lecture_".$lecture." FROM ".$studentData['department']."_timetable WHERE section='$section' AND day='".strtolower($day)."' AND semester='$semester'";
												$result = $conn->query($query);
												$data = $result->fetch_array();
												$lectureData = explode(":", $data[0]);
												$subjectCode = $lectureData[0];
												$teacherCode = $lectureData[1];
												$query = "SELECT * FROM teacher_list WHERE teacher_code='".$teacherCode."'";
												$result = $conn->query($query);
												$teacher = $result->fetch_array()['initials'];
												echo $teacher;
											}
    										?>
                                    </h1>
                                </div>
                            </div>
                        </div>

                        <!-- Percentage -->
                        <div class="col-lg col-md-3 col-xs-6 col-sm-6">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title text-center pr-3">Subject</h4>
                                </div>
                                <div class="card-body py-4">
                                    <h1 class="text-center">
                                        <?php
    										if($lecture == 0) {
												echo "-";
											} else {
												$query = "SELECT * FROM subjects WHERE subject_code='".$subjectCode."'";
												$result = $conn->query($query);
												$subject = $result->fetch_array()['subject_initials'];
												echo $subject;
											}
    										?>
                                    </h1>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- First Row Ending -->
                </div>
                <?php
                if($day != 'Saturday' && $day != 'Sunday') {
                ?>
                <div class="my-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between card-header-primary">
                            <h4 class="card-title mt-2">Today's Seating Plan</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-center">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>Lecture</th>
                                            <th>Class Room</th>
                                            <th>Teacher</th>
                                            <th>Subject</th>
                                        </tr>
                                    </thead>
                                    <?php
												$query = "SELECT * FROM ".$studentData['department']."_classes WHERE semester=$semester AND section='$section' AND day='".strtolower($day)."'";
												$result = $conn->query($query);
												$classroomData = array();
												while($row = $result->fetch_array()) {
													array_push($classroomData, strtoupper(substr($row['classroom'], 0, 1)) . substr($row['classroom'], 1));
												}

												// print_r($classroomData);

												
												for($i = 1; $i <= 8; $i++) {
													echo "<tr><td>$i</td><td>".$classroomData[$i - 1]."</td>";


													$query = "SELECT lecture_".$i." FROM ".$studentData['department']."_timetable WHERE section='$section' AND day='".strtolower($day)."' AND semester='$semester'";
													$result = $conn->query($query);
													$data = $result->fetch_array();
													$lectureData = explode(":", $data[0]);
													$subjectCode = $lectureData[0];
													$teacherCode = $lectureData[1];
													$query = "SELECT * FROM teacher_list WHERE teacher_code='".$teacherCode."'";
													$result = $conn->query($query);
													$teacher = $result->fetch_array();
													echo "<td>".$teacher['name']." (".$teacher['initials'].")</td>";

													$query = "SELECT * FROM subjects WHERE subject_code='".$subjectCode."'";
													$result = $conn->query($query);
													$subject = $result->fetch_array();
													echo "<td>".$subject['subject_initials']." (".substr($subject['lecture_type'], 0, 1).")</td>";

													echo "</tr>";
												}
                                                    
            									?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
				// </div>
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
    <script src="./assets/sidebarSript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
    $(document).ready(function() {
        $(".loader-container").css("display", "none");
    });
    </script>
    <script>
    var configPractical = {
        type: 'line',
        data: {
            labels: [ < ? php
                $temp = "";
                foreach($practicalSubjects as $subject) {
                    $temp. = "'".$subject['subject'].
                    " (P)',";
                }
                echo substr($temp, 0, -1); ?
                >
            ],
            datasets: [{
                label: 'Total Lectures',
                fill: false,
                backgroundColor: "#ED5D7C",
                borderColor: "#FF2D39",
                data: [ < ? php
                    $temp = "";
                    foreach($practicalSubjects as $subject) {
                        $temp. = "'".$subject['totalLectures'].
                        "',";
                    }
                    echo substr($temp, 0, -1); ?
                    >
                ],
            }, {
                label: 'Attended Lectures',
                fill: true,
                backgroundColor: "#A0CDD9",
                borderColor: "#3298DB",
                data: [ < ? php
                    $temp = "";
                    foreach($practicalSubjects as $subject) {
                        $temp. = "'".$subject['attendedLectures'].
                        "',";
                    }
                    echo substr($temp, 0, -1); ?
                    >
                ],
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
            labels: [ < ? php
                $temp = "";
                foreach($theorySubjects as $subject) {
                    $temp. = "'".$subject['subject'].
                    " (T)',";
                }
                echo substr($temp, 0, -1); ?
                >
            ],
            datasets: [{
                label: 'Total Lectures',
                fill: false,
                backgroundColor: "#ED5D7C",
                borderColor: "#FF2D39",
                data: [ < ? php
                    $temp = "";
                    foreach($theorySubjects as $subject) {
                        $temp. = "'".$subject['totalLectures'].
                        "',";
                    }
                    echo substr($temp, 0, -1); ?
                    >
                ],
            }, {
                label: 'Attended Lectures',
                fill: true,
                backgroundColor: "#A0CDD9",
                borderColor: "#3298DB",
                data: [ < ? php
                    $temp = "";
                    foreach($theorySubjects as $subject) {
                        $temp. = "'".$subject['attendedLectures'].
                        "',";
                    }
                    echo substr($temp, 0, -1); ?
                    >
                ],
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