<?php

require_once './includes/connection.php';
require_once './includes/authentication.php';
$query = "SELECT * FROM teacher_list WHERE teacher_username='" . $_SESSION['Username'] . "'";
$teacherData = $conn->query($query)->fetch_array();
if ($_SESSION['userType'] == "Student") {
  header("Location: student-dashboard.php");
}
?>
<?php
$day = date("l");
$month = date("n");
if($month <= 6) {
  $semesters = "2, 4, 6";
} else {
  $semesters = "1, 3, 5";
}

$query = "SELECT * FROM " . $teacherData['department'] . "_timetable WHERE day='" . strtolower($day) . "' AND semester IN($semesters)";
$result = $conn->query($query);
$counter = 0;
$timetable = array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
  $timetable[$counter] = array();
  for ($i = 4; $i <= 11; $i++) {
    // To check for the adjustments
    $newQuery = "SELECT * FROM adjustments WHERE date='" . date("Y-m-j") . "' AND assigned_teacher='" . $teacherData['teacher_code'] . "' AND lecture_no='" . ($i - 3) . "'";
    $res = $conn->query($newQuery);
    if ($res->num_rows > 0) {
      $subjectData = $conn->query($newQuery)->fetch_array();
      $qry = "SELECT * FROM subjects WHERE department='" . $teacherData['department'] . "' AND subject_code='" . $subjectData['subject_code'] . "'";
      $res = $res->fetch_array();
      $subjectData = $conn->query($qry)->fetch_array();
      $sec = explode(",", $res['section'])[0];
      $newQry = "SELECT lecture_" . ($i - 3) . " FROM " . $teacherData['department'] . " WHERE date='" . date("Y-m-j") . "' AND section='" . $sec . "' AND semester='" . $subjectData['semester'] . "'";
      if ($conn->query($newQry)->fetch_array()[0] == "NA" || $conn->query($newQry)->fetch_array()[0] == "") {
        $timetable[$counter][$i - 3] = array(
          "subject" => $subjectData['subject_initials'],
          "subjectName" => $subjectData['subject_name'],
          "type" => $subjectData['lecture_type'],
          "subjectCode" => $subjectData['subject_code'],
          "assignedLecture" => true
        );
        $timetable[$counter]['semester'] = $subjectData['semester'];
        $timetable[$counter]['section'] = $res['section'];
      }
    }
    $myQuery = "SELECT lecture_" . ($i - 3) . " FROM " . $teacherData['department'] . " WHERE date='" . date("Y-m-d") . "' AND section='" . $row[3] . "' AND semester='" . $row[1] . "'";
    if ($conn->query($myQuery)->fetch_array()[0] != "NA" && $conn->query($myQuery)->fetch_array()[0] != "") {
      continue;
    }
    if (@array_search($teacherData['teacher_code'], explode(",", explode(":", $row[$i])[1])) !== FALSE) {
      $newQuery = "SELECT * FROM subjects WHERE subject_code='" . explode(":", $row[$i])[0] . "' AND department='" . $teacherData['department'] . "' AND semester=" . $row[1];
      $subjectData = $conn->query($newQuery)->fetch_array();
      $timetable[$counter][$i - 3] = array(
        "subject" => $subjectData['subject_initials'],
        "subjectName" => $subjectData['subject_name'],
        "type" => $subjectData['lecture_type'],
        "subjectCode" => $subjectData['subject_code']
      );
    }
  }
  if (count($timetable[$counter]) >= 1) {
    if (!isset($timetable[$counter]['semester']))
      $timetable[$counter]['semester'] = $row[1];
    if (!isset($timetable[$counter]['section']))
      $timetable[$counter]['section'] = $row[3];
  }
  $counter++;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Attendance GNDPC
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
    <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/main_styles.css">
    <!-- CSS Files -->
    <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
    <style>
    .date-card {
        margin-top: 0 !important;
    }

    .date-card-header {
        margin: 0 0 !important;
    }
    </style>
    <script src="./assets/js/core/jquery.min.js"></script>
    <?php
  include("./loader.php");
  ?>
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
  require './includes/nav.php';
  ?>
    <div class="wrapper ">
        <div class="main-panel">
            <?php
      require_once './includes/header.php';
      ?>
            <div class="content">
                <div class="container-fluid">
                    <!--day and date -->

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card date-card">
                                <div class="card-header card-header-primary date-card-header">
                                    <h4 class="card-title">Date : <?php echo date("j-m-Y") ?><span
                                            style="float:right">Day : <?php echo $day ?></span></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--day and date end-->
                    <div class="row">
                        <?php
            $query = "SELECT sec FROM departments WHERE initials='" . $teacherData['department'] . "'";
            $sections = $conn->query($query)->fetch_array()[0];
            $date = date("Y-m-d");

            for ($i = 1; $i <= 7; $i++) {
              for ($j = 0; $j < count($timetable); $j++) {
                if (isset($timetable[$j][$i])) {
                  if ($timetable[$j][$i]['type'] == "Practical") {
                    $lectures = $i;
                    while (isset($timetable[$j][$i + 1]) && $timetable[$j][$i]['subject'] === $timetable[$j][$i + 1]['subject']) {
                      $lectures .= ", " . ($i + 1);
                      $i++;
                    }

                    echo '       
                  <div class="col-lg-4 col-md-12">
                    <div class="card custom_attendance_card" onclick="takeAttendance(this)" data-lecture="' . $lectures . '" data-subject="' . $timetable[$j][$i]['subjectName'] . '" data-subject-initials="' . $timetable[$j][$i]['subject'] . '" data-section="' . $timetable[$j]['section'] . '" data-department="' . $teacherData['department'] . '" data-semester="' . $timetable[$j]['semester'] . '" data-subject-code="' . $timetable[$j][$i]['subjectCode'] . '">

                      <div class="card-header card-header-primary">
                        <h4 class="card-title">Lecture ' . $lectures . '</h4>
                        <p class="card-category">' . $timetable[$j][$i]['subjectName'] . '</p>
                      </div>
                      <div class="card-body table-responsive">
                          <table class="table text-left">
                            <tbody>
                              <tr>
                                <td>
                                  Type
                                </td>
                                <td>
                                  Practical
                                </td>
                              </tr>

                              <tr>
                                <td>
                                  Subject
                                </td>
                                <td>
                                  ' . $timetable[$j][$i]['subject'] . '
                                </td>
                              </tr>

                              <tr>
                                <td>
                                  Semester
                                </td>
                                <td>
                                  ' . $timetable[$j]['semester'] . '
                                </td>

                              </tr>
                              <tr>
                                <td>
                                  Section
                                </td>
                                <td>
                                  ' . $timetable[$j]['section'] . '
                                </td>
                              </tr>
                        </tbody>
                          </table>

                          </div>
                      </div>
                    </div>';
                  } else {
                    echo '
                                                    <div class="col-lg-4 col-md-12">
                                                    <div class="card custom_attendance_card" onclick="takeAttendance(this)" data-lecture="' . $i . '" data-subject="' . $timetable[$j][$i]['subjectName'] . '" data-subject-initials="' . $timetable[$j][$i]['subject'] . '" data-section="' . $sections   . '" data-department="' . $teacherData['department'] . '" data-semester="' . $timetable[$j]['semester'] . '" data-subject-code="' . $timetable[$j][$i]['subjectCode'] . '">

                                                      <div class="card-header card-header-primary">
                                                        <h4 class="card-title">Lecture ' . $i . '</h4>
                                                        <p class="card-category">' . $timetable[$j][$i]['subjectName'] . '</p>
                                                      </div>
                                                      <div class="card-body table-responsive">
                                                          <table class="table text-left">
                                                            <tbody>
                                                              <tr>
                                                                <td>
                                                                  Type
                                                                </td>
                                                                <td>
                                                                  Lecture
                                                                </td>

                                                              </tr>
                                                              <tr>
                                                                <td>
                                                                  Subject
                                                                </td>
                                                                <td>
                                                                 ' . $timetable[$j][$i]['subject'] . '
                                                                </td>

                                                              </tr>
                                                              <tr>
                                                                <td>
                                                                  Semester
                                                                </td>
                                                                <td>
                                                                 ' . $timetable[$j]['semester'] . '
                                                                </td>

                                                              </tr>
                                                              <tr>
                                                                <td>
                                                                  Section
                                                                </td>
                                                                <td>
                                                                  ' . $sections . '
                                                                </td>
                                                              </tr>
                                                            </tbody>
                                                          </table>
                                                      </div>
                                                    </div>
                                                    </div>';
                  }
                  break;
                }
              }
            }
            ?>
                    </div>
                </div>
            </div>
            <?php
      require './includes/footer.php';
      ?>
        </div>
    </div>

    <!--   Core JS Files   -->
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
    <script>
    $(document).ready(function() {
        $(".loader-container").css("display", "none");
    });

    function takeAttendance(el) {
        window.location.href = "attendance.php?lecture=" + el.getAttribute("data-lecture") + "&sections=" + el
            .getAttribute("data-section") + "&department=" + el.getAttribute("data-department") + "&semester=" + el
            .getAttribute("data-semester") + "&subjectInitials=" + el.getAttribute("data-subject-initials") +
            "&subject=" + encodeURIComponent(el.getAttribute("data-subject")) + "&subjectCode=" + el.getAttribute(
                "data-subject-code");
    }
    </script>

    <script src="assets/sidebarSript.js"></script>

</body>

</html>