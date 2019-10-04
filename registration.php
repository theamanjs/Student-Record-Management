<?php

    require_once './includes/connection.php';
    require_once './includes/authentication.php';
    $query = "SELECT * FROM teacher_list WHERE teacher_username='" . $_SESSION['Username'] . "'";
    $teacherData = $conn->query($query)->fetch_array();
    if($_SESSION['userType'] == "Student") {
        header("Location: student-dashboard.php");
      }
        ?>
         <?php
              $day = date("l");
              $query = "SELECT * FROM " .$teacherData['department']. "_timetable WHERE day='".strtolower($day)."'";
              $result = $conn->query($query);
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
            <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
            <!--     Fonts and icons     -->
            <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
            <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
            <link rel="stylesheet" href="./assets/main_styles.css">
            <!-- CSS Files -->
            <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
            <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">
            <style>
            .date-card{
              margin-top:0 !important;
            }
              .date-card-header{
              margin:0 0 !important;
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
                                        <h4 class="card-title">Date : <?php echo date("j-m-Y") ?><span style="float:right">Day : <?php echo $day ?></span></h4>
                                      </div>
                                    </div>
                                  </div>
                                </div>


<div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header card-header-primary">
                                             <h4 class="card-title">Registration Form
                                        </h4>
                                    </div>
                                        <div class="card-body">
                                            <form id="addSubjectForm" class="custom_add_form">
                                                <div class="row justify-content-between">
                                                <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating">Blood Group
                                                                </label>
                                                                <input type="text" class="form-control" name="blood-group" id="blood-group" required>
                                                            </div>
                                                        </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">Class Roll no
                                                                </label>
                                                                <input type="text" name="subjectName" id="class-roll-no" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                                <label class="bmd-label-floating">Branch of Engg.
                                                                </label>
                                                                <input type="text" class="form-control" name="branch" id="branch" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                                <label>Date of admission
                                                                </label>
                                                                <input type="date" class="form-control" name="dob" id="dob" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                            <label for="semester">Category
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" id="category" name="category">
                                                                   <option>General</option>
                                                                   <option>OBC</option>
                                                                   <option>SC</option>
                                                                   <option>ST</option>
                                                                   </select>
                                                        </div>
                                                    </div>

                                                <div class="row">
                                                    <div class="col-md-9">

                                                    <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                                <label class="bmd-label-floating">Name of the student
                                                                </label>
                                                                <input type="text" class="form-control" name="name" id="name" required>
                                                        </div>
                                                    </div>
                                                    </div>

                                                    <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                                <label>Date of Birth
                                                                </label>
                                                                <input type="date" class="form-control" name="dob" id="dob" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="semester">Category
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" id="category" name="category">
                                                                   <option>General</option>
                                                                   <option>OBC</option>
                                                                   <option>SC</option>
                                                                   <option>ST</option>
                                                                   </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                                <label class="bmd-label-floating">Adhaar No
                                                                </label>
                                                                <input type="text" class="form-control" name="adhaar" id="adhaar" required>
                                                        </div>
                                                    </div>
                                                    </div>


                                                    <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label class="bmd-label-floating">Bank A/c No of the student
                                                                </label>
                                                                <input type="text" class="form-control" name="bank" id="bank" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label class="bmd-label-floating">Email
                                                                </label>
                                                                <input type="text" class="form-control" name="email" id="email" required>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                pic1
                                                </div>
                                            </div>

                                                    <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label class="bmd-label-floating">Correspondence Address
                                                                </label>
                                                                <textarea class="form-control" name="c-address" id="c-address" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label class="bmd-label-floating">Premanent Address
                                                                </label>
                                                                <textarea class="form-control" name="p-address" id="p-address" required></textarea>
                                                        </div>
                                                    </div>
                                                    </div>


                                                    <div class="row">
            <div class="col-md-12">
            <h4>Academic Qualifications :</h4>
              <div class="card">   
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th>
                          Examination Passed
                        </th>
                        <th>
                          Year of Passing
                        </th>
                        <th>
                          University/Board
                        </th>
                        <th>
                          Maximum Marks
                        </th>
                        <th>
                          Marks Obtained
                        </th>
                        <th>
                          Marks %
                        </th>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            10th
                          </td>
                          <td>
                            <input type="text" class="form-control" name="bank" id="bank" required>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="bank" id="bank" required>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="bank" id="bank" required>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="bank" id="bank" required>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="bank" id="bank" required>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            10+2
                          </td>
                          <td>
                            Minerva Hooper
                          </td>
                          <td>
                            Curaçao
                          </td>
                          <td>
                            Sinaai-Waas
                          </td>
                          <td class="text-primary">
                            $23,789
                          </td>
                        </tr>
                        <tr>
                          <td>
                            ITI
                          </td>
                          <td>
                            Sage Rodriguez
                          </td>
                          <td>
                            Netherlands
                          </td>
                          <td>
                            Baileux
                          </td>
                          <td class="text-primary">
                            $56,142
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            </div>


                                            <div class="row">
                                                <div class="col-md-9">

                                                    <div class="row">
                                                    <div class="col-md-7">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Father's Name
                                                                </label>
                                                                <input type="text" class="form-control" name="fname" id="fname" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                    <div class="form-group">
                                                                <label>Father's DOB
                                                                </label>
                                                                <input type="date" class="form-control" name="fdob" id="fdob" required>
                                                        </div>
                                                    </div>
                                                    </div>

                                                    <div class="row">
                                                    <div class="col-md-12">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Profession (Designation & Official Address, if in service)
                                                                </label>
                                                                <input type="text" class="form-control" name="profession" id="profession" required>
                                                        </div>
                                                    </div>
                                                    </div>

                                                    <div class="row">
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Telephone No. (O)
                                                                </label>
                                                                <input type="text" class="form-control" name="telephone-o" id="telephone-o" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">(R)
                                                                </label>
                                                                <input type="text" class="form-control" name="telephone-r" id="telephone-r" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Mobile
                                                                </label>
                                                                <input type="text" class="form-control" name="mobile" id="mobile" required>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                pic2
                                                </div>
                                            </div> 




                                            <div class="row">
                                                <div class="col-md-9">

                                                    <div class="row">
                                                    <div class="col-md-7">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Mother's Name
                                                                </label>
                                                                <input type="text" class="form-control" name="mname" id="mname" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                    <div class="form-group">
                                                                <label>Mother's DOB
                                                                </label>
                                                                <input type="date" class="form-control" name="mdob" id="mdob" required>
                                                        </div>
                                                    </div>
                                                    </div>

                                                    <div class="row">
                                                    <div class="col-md-12">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Profession (Designation & Official Address, if in service)
                                                                </label>
                                                                <input type="text" class="form-control" name="m-profession" id="m-profession" required>
                                                        </div>
                                                    </div>
                                                    </div>

                                                    <div class="row">
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Telephone No. (O)
                                                                </label>
                                                                <input type="text" class="form-control" name="m-telephone-o" id="m-telephone-o" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">(R)
                                                                </label>
                                                                <input type="text" class="form-control" name="m-telephone-r" id="m-telephone-r" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Mobile
                                                                </label>
                                                                <input type="text" class="form-control" name="m-mobile" id="m-mobile" required>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                pic3
                                                </div>
                                            </div> 



                                            <div class="row">
                                                <div class="col-md-9">

                                                    <div class="row">
                                                    <div class="col-md-12">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Guardian's Name
                                                                </label>
                                                                <input type="text" class="form-control" name="gname" id="gname" required>
                                                        </div>
                                                    </div>
                                                    </div>

                                                    <div class="row">
                                                    <div class="col-md-12">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Relation with Student and Correspondence Address
                                                                </label>
                                                                <input type="text" class="form-control" name="relation" id="relation" required>
                                                        </div>
                                                    </div>
                                                    </div>

                                                    <div class="row">
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Telephone No. (O)
                                                                </label>
                                                                <input type="text" class="form-control" name="g-telephone-o" id="g-telephone-o" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">(R)
                                                                </label>
                                                                <input type="text" class="form-control" name="g-telephone-r" id="g-telephone-r" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                                <label class="bmd-label-floating">Mobile
                                                                </label>
                                                                <input type="text" class="form-control" name="g-mobile" id="g-mobile" required>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                pic4
                                                </div>
                                            </div> 


                                                   


                                                    




                                                    
                                                    
                                                    <button type="submit" class="btn btn-primary add_submit_button">Submit
                                                    </button>
                                                    <div class="clearfix">
                                                    </div>
                                                </form>
                                            </div>
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

                <!--   Core JS Files   -->
                <script src="./assets/js/core/popper.min.js"></script>
                <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
                <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
                <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
                <script src="assets/js/core/bootstrap-select.js"></script>
                <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
                <script>
                    $(document).ready(function() {
                      $(".loader-container").css("display","none");
                    });

                    function takeAttendance(el) {
                        window.location.href = "attendance.php?lecture=" + el.getAttribute("data-lecture") + "&sections=" + el.getAttribute("data-section") + "&department=" + el.getAttribute("data-department") + "&semester=" + el.getAttribute("data-semester") + "&subjectInitials=" + el.getAttribute("data-subject-initials") + "&subject=" + encodeURIComponent(el.getAttribute("data-subject")) + "&subjectCode=" + el.getAttribute("data-subject-code");
                    }

         </script>

                <script src="assets/sidebarSript.js"></script>

        </body>

        </html>