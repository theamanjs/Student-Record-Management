<?php
   require_once './includes/connection.php';
   require_once './includes/authentication.php';
   if($_SESSION['userType'] == "Student") {
    header("Location: student-dashboard.php");
    }
    else if($_SESSION['userType'] == "lecturer" || $_SESSION['userType'] == "principal"){
    header("Location: dashboard.php");
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
            Add new - GNDPC
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
        <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
        <link rel="stylesheet" href="./assets/main_styles.css">
        <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">

        <style type="text/css">

        </style>
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
            require './includes/nav.php';
            ?>
                <div class="main-panel">
                    <?php
                       require './includes/header.php';
                           ?>
                            <div class="content">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header card-header-primary">
                                             <h4 class="card-title">Add Subject
                                        </h4>
                                    </div>
                                        <div class="card-body">
                                            <form id="addSubjectForm" class="custom_add_form">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">Subject name
                                                                </label>
                                                                <input onchange="genInitials(this.value)" type="text" name="subjectName" id="subjectName" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating">Subject code
                                                                </label>
                                                                <input type="text" class="form-control" name="subjectCode" id="subjectCode" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating">Subject initials
                                                                </label>
                                                                <input type="text" class="form-control" name="subjectInitials" id="subjectInitials" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="semester">Semester
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" id="semester" name="semester" onchange="fetchTeachers()">
                                                                    <?php
                                                                         for ($i = 1; $i <= 6; $i++) {
                                                                         echo "<option>" . $i . "</option>";
                                                                             }
                                                                         ?>
                                                                   </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="department">Department
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" id="department" name="department" onchange="fetchTeachers()">
                                                                    <?php
                                                                         $query = "SELECT * FROM departments";
                                                                         $result = $conn->query($query);
                                                                         while ($data = $result->fetch_array()) {
                                                                         if ($data[2] !== "as") {
                                                                         echo "<option value=" . $data[2] . "> " . $data[1] . " </option>";
                                                                         }
                                                                         }
                                                                         ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="teacherCode">Teacher Name
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" id="teacherCode" name="teacherCode">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="subjectType">Subject Type
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" id="subjectType" name="subjectType">
                                                                    <option>Theory
                                                                    </option>
                                                                    <option>Practical
                                                                    </option>
                                                                </select>
                                                            </div>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header card-header-primary">
                                                <h4 class="card-title">Add Teacher
                                            </h4>
                                        </div>
                                            <div class="card-body">
                                                <form id="addTeacherForm" class="custom_add_form">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating">Teacher name
                                                                </label>
                                                                <input type="text" class="form-control" onchange="work(this.value)" id="teacherName" name="teacherName" required>
                                                            </div>
                                                    </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating">Teacher initials
                                                                </label>
                                                                <input type="text" class="form-control" name="teacherInitials" id="teacherInitials" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="bmd-label-floating">Username
                                                                    </label>
                                                                <input type="text" class="form-control" name="teacherUsername" id="teacherUsername" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating">Password
                                                                </label>
                                                                <input type="text" class="form-control" name="teacherPassword" id="teacherPassword" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating">Designation
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" name="teacherDesignation" value="select">
                                                                    <option>Lecturer
                                                                    </option>
                                                                    <option>HOD
                                                                    </option>
                                                                    <option>Clerk
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating">Designation
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" name="teacherDepartment" value="select">
                                                                    <?php
                                                                         $query = "SELECT * FROM departments";
                                                                         $result = $conn->query($query);
                                                                         while($data = $result->fetch_array()) {
                                                                         echo "<option value=".$data[2]."> ".$data[1]. "</option>";
                                                                         }
                                                                         ?>
                                                                </select>
                                                            </div>
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
        <script src="./assets/js/core/jquery.min.js"></script>
        <script src="./assets/js/core/popper.min.js"></script>
        <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
        <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="assets/js/core/bootstrap-select.js"></script>
        <script src="./assets/js/plugins/bootstrap-notify.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
        <script>
            $("#addSubjectForm").on("submit", function(e) {
                e['originalEvent'].preventDefault();
                addSubject();
            });
            $("#addTeacherForm").on("submit", function(e) {
                e['originalEvent'].preventDefault();
                addTeacher();
            });

            function addSubject() {
                let data = new FormData($("#addSubjectForm")[0]);
                data.append("addSubject", true);
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response == "true") {
                            md.showNotification('top', 'center', 'Subject Added!');
                        } else {
                            md.showNotification('top', 'center', 'Subject Not Added!');
                        }
                    }
                });
            }
            fetchTeachers();

            function fetchTeachers() {
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        department: $("#department").val(),
                        semester: $("#semester").val(),
                        fetchTeachers: true
                    },
                    success: function(response) {
                        $("#teacherCode").html(response);
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }

            function genInitials(value) {
                let letter = "";
                value.split(" ").forEach(init => letter += init.charAt(0));
                document.getElementById('subjectInitials').value = letter.toUpperCase();
            }
            "use strict";

            function work(value) {
                //Generating Initials 
                let initial = "";
                value.split(" ").forEach(init => initial += init.charAt(0));
                document.getElementById('teacherInitials').value = initial.toUpperCase();
                //Generating Random username
                //let withoutSpace = value.replace(/ /gi, '');
                let randomNumber = Math.floor(Math.random() * (999 - 100) + 100);
                let randomName = value.split(" ")[0] + randomNumber;
                if (value.length > 4)
                    document.getElementById('teacherUsername').value = randomName.toLowerCase();
                document.getElementById('teacherPassword').value = randomName.toLowerCase();
            }

            function addTeacher() {
                let data = new FormData($("#addTeacherForm")[0]);
                data.append("addTeacher", true);
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response == "true") {
                            md.showNotification('top', 'center', 'Teacher Added!');
                        } else {
                            md.showNotification('top', 'center', 'Teacher Not Added!');
                        }
                    }
                });
            }
            $(document).ready(function() {
                $(".loader-container").css("display", "none");
                $('.inner.show').perfectScrollbar();
            });
        </script>
        <script src="assets/sidebarSript.js"></script>
    </body>

    </html>