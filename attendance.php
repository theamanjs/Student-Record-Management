


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
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
    <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link rel="stylesheet" href="./assets/main_styles.css">
    <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />

        <style>
            .date-card-header{
              margin:0 0 !important;
            }
            .date-card{
                margin-top:0 !important;
            }
            @media only screen and (min-width: 768px){
            .date-card-title span{
                margin-right:30px;
            }
            }
            .cancel-button{
                background-color:#FFFFFF !important;
                color:var(--themeColorDark) !important;
            }
            .row .cancel-button:active{
                background-color:#FFFFFF !important;
                color:var(--themeColorDark) !important;
                border:0 !important;
            }
             

        </style>
    <script src="./assets/js/core/jquery.min.js"></script>

</head>

<body class="">
     <!-- LOADER -->
<div class="loader-container">
    <div class="loader-card">
        <div class="loader"></div>
        <div class="loader-text">Loading...</div>
    </div>
</div>
<!-- END OF LOADER -->
    <script>
      let colorDark = localStorage.getItem("themeColorDark");
      let colorLight = localStorage.getItem("themeColorLight");
      if (typeof colorDark != "undefined" && colorDark != null && colorLight != "undefined" && colorLight != null){
        document.body.style.setProperty('--themeColorDark', colorDark);
        document.body.style.setProperty('--themeColorLight', colorLight);
        document.body.style.setProperty('--themeColorShadow', colorDark+'66');
      }
    </script>
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
                       <div class="row">
                            <div class="card date-card">
                                <div class="card-header card-header-primary date-card-header">
                                    <h4 class="card-title date-card-title">
                                    <div class="row">
                                    <div class="col-lg-2 col-md-6">Lecture : <?php echo $_GET['lecture'] ?></div> 
                                    <div class="col-lg-4 col-md-6 date-card-sem">Subject : <?php echo $_GET['subject'] ?></div> 
                                    <div class="col-lg-3 col-md-6">Section : <?php echo $_GET['sections'] ?></div> 
                                    <div class="col-lg-3 col-md-6">Semester : <?php echo $_GET['semester'] ?></div>
                                    <div class="col-lg-2 col-md-12 d-xl-none d-md-none"><a class="btn btn-primary col-sm-12 cancel-button" href="dashboard.php">Cancel</a></div>
                                    </div>
                                    </h4>
                                </div>
                            </div>
                        </div>
                            <div>
                                <form class="row ">
                                    <?php
                                       $query = "SELECT sec FROM departments WHERE initials='" . $_GET['department'] . "'";
                                       $sections = explode(",", $conn->query($query)->fetch_array()[0]);
                                       $query = "SELECT * FROM `roll_series` WHERE department='" . $_GET['department'] . "' AND semester='" . $_GET['semester'] . "'";
                                       $result = $conn->query($query);
                                       while ($row = $result->fetch_array()) {
                                             if (count(explode(",", $_GET['sections'])) === 2) {
                                             if ($row['status'] == "1") {
                                              echo '<div class="col-4 col-md-2 text-center pt-3">
                                                   <label class="tgl">
                                                   <input type="checkbox" data-val="' . $row['roll_no'] . '" id="rollNumberSeq' . $row['roll_no'] . '" checked/>
                                                   <span class="tgl_body">
                                                     <span class="tgl_switch"></span>
                                                     <span class="tgl_track">
                                                       <span class="tgl_bgd"></span>
                                                       <span class="tgl_bgd tgl_bgd-negative"></span>
                                                     </span>
                                                   </span>
                                                   </label>
                                                   <label class="roll-no pl-2" for="rollNumberSeq' . $row['roll_no'] . '"> ' . $row['roll_no'] . '
                                                   </label>
                                               </div>';
                                         }
                                       }
                                       else {
                                           if ($_GET['sections'] == $sections[0]) {
                                               if (substr($row['roll_no'], -2) >= 1 && substr($row['roll_no'], -2) <= 50 && $row['status'] == "1") {
                                                   echo '<div class="col-4 col-md-2 text-center pt-3">
                                                   <label class="tgl">
                                                   <input type="checkbox" data-val="' . $row['roll_no'] . '" id="rollNumberSeq' . $row['roll_no'] . '" checked/>
                                                   <span class="tgl_body">
                                                     <span class="tgl_switch"></span>
                                                     <span class="tgl_track">
                                                       <span class="tgl_bgd"></span>
                                                       <span class="tgl_bgd tgl_bgd-negative"></span>
                                                     </span>
                                                   </span>
                                                   </label>
                                                   <label class="roll-no pl-2" for="rollNumberSeq' . $row['roll_no'] . '"> ' . $row['roll_no'] . '
                                                   </label>
                                               </div>';
                                       } 
                                       } else if ($_GET['sections'] == $sections[1]) {
                                               if (substr($row['roll_no'], -2) >= 51 && substr($row['roll_no'], -2) <= 99 && $row['status'] == "1") {
                                                   echo '<div class="col-4 col-md-2 text-center pt-3">
                                                   <label class="tgl">
                                                   <input type="checkbox" data-val="' . $row['roll_no'] . '" id="rollNumberSeq' . $row['roll_no'] . '" checked/>
                                                   <span class="tgl_body">
                                                     <span class="tgl_switch"></span>
                                                     <span class="tgl_track">
                                                       <span class="tgl_bgd"></span>
                                                       <span class="tgl_bgd tgl_bgd-negative"></span>
                                                     </span>
                                                   </span>
                                                   </label>
                                                   <label class="roll-no pl-2" for="rollNumberSeq' . $row['roll_no'] . '"> ' . $row['roll_no'] . '
                                                   </label>
                                               </div>'; 
                                             }
                                           }
                                       }
                                       }
                                       ?>
                                     </div>
                                   <div class="col-md-12 align-center attendanceForm d-flex justify-content-between">
                                <button class="btn btn-primary attendanceSubmit">Submit</button>
                                <a class="btn btn-primary attendanceSubmit d-md-block d-none" href="dashboard.php">Cancel</a>
                                </form>
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
            $("form").on("submit", function(e) {
                e['originalEvent'].preventDefault();
                let sectionFirst = "";
                let sectionSecond = "";
                $("input[type=checkbox]:checked").each((index, element) => {
                    if (parseInt(element.getAttribute("data-val").slice(-2)) <= 50)
                        sectionFirst += element.getAttribute("data-val") + ",";
                    else
                        sectionSecond += element.getAttribute("data-val") + ",";
                });
                sectionFirst = sectionFirst.slice(0, -1);
                sectionSecond = sectionSecond.slice(0, -1);

                $(".loader-container").css("display", "flex");
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        sectionFirst,
                        sectionSecond,
                        sendAttendance: true,
                            lectures: "<?php echo $_GET['lecture']; ?>",
                            sections: "<?php echo $_GET['sections']; ?>",
                            department: "<?php echo $_GET['department']; ?>",
                            semester: "<?php echo $_GET['semester']; ?>",
                            subjectCode: "<?php echo $_GET['subjectCode']; ?>"
                    },
                    success: function(response) {
                        window.location.href = "dashboard.php";
                    }
                });
            });

            function takeAttendance(el) {
                window.location.href = "attendance.php?lecture=" + el.getAttribute("data-lecture") + "&sections=" + el.getAttribute("data-section") + "&department=" + el.getAttribute("data-department") + "&semester=" + el.getAttribute("data-semester") + "&subjectInitials=" + el.getAttribute("data-subject-initials") + "&subject=" + encodeURIComponent(el.getAttribute("data-subject"));
            }
        </script>
        <script src="assets/sidebarSript.js"></script>
</body>

</html>
<?php
if($_SESSION['userType'] == "Student") {
  header("Location: student-dashboard.php");
  }
?>