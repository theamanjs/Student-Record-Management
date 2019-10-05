<?php

    require_once './includes/connection.php';
    require_once './includes/authentication.php';
    $query = "SELECT * FROM teacher_list WHERE teacher_username='" . $_SESSION['Username'] . "'";
    $teacherData = $conn->query($query)->fetch_array();
    
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
    <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">
    <style>
    .date-card {
        margin-top: 0 !important;
    }

    .date-card-header {
        margin: 0 0 !important;
    }

    .date-card-header .btn-primary:hover,
    .date-card-header .btn-primary:focus {
        background-color: var(--themeColorLight) !important;
        color: #FFFFFF !important;
    }
    .sub-assm{
  color:var(--themeColorDark) !important;
}
.sub-assm:hover{
  color:var(--themeColorDark) !important;
  cursor:pointer !important; 
}

    </style>
    <script src="./assets/js/core/jquery.min.js"></script>
    <?php
include("./loader.php");
?>
</head>

<body class="">


    <?php if($teacherData['designation'] == 'hod' || $teacherData['designation'] == 'lecturer') {
?>
    <div class="modal" tabindex="-1" role="dialog" id="addAssignmentModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header custom_modal_header">
                    <h5 class="modal-title custom_modal_title">Add Assignment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form class="custom_add_form" method="post" id="addAssignmentForm">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Assignment Number
                                    </label>
                                    <input type="text" name="assignment-no" id="assignment-no" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Submission Date
                                    </label>
                                    <input type="date" name="submission-date" id="submission-date" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="custom_add_form form-group col-12">
                            <label for="assignment-file" class="bmd-label-floating file-label">Document
                                Attachment</label>
                            <input type="file" name="assignment-file" id="assignment-file"
                                onchange="updateFileLabel(this)">
                            <a href="#" class="btn btn-sm btn-primary float-right add_submit_button"
                                onclick="$('#assignment-file').trigger('click');">Select File</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Assignment text
                                    </label>
                                    <textarea name="assignment-text" id="assignment-text" class="form-control"
                                        required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">

                                <label for="subjectList" class="col-form-label col-md-12">Semester</label>
                                <select class="selectpicker custom-select1 col-md-12" id="semester" name="semester" onchange="fetchSubjects(this)">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="subjectList" class="col-form-label col-md-12">Subjects</label>
                                <select class="selectpicker custom-select1 col-md-12" id="subject" name="subject">
                                </select>
                            </div>

                        </div>

                        <div class="form-group col-12 pr-4 text-right">
                            <button class="btn btn-primary ml-2 custom-search-button" type="button"
                                onclick="addAssignment()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="modal" tabindex="-1" role="dialog" id="submitAssignment">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header custom_modal_header">
                    <h5 class="modal-title custom_modal_title">Submit Assignment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form class="custom_add_form" method="post" id="submitAssignmentForm">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Assignment Number
                                    </label>
                                    <input type="text" name="assignment-no" id="assignment-no" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Submission Date
                                    </label>
                                    <input type="date" name="submission-date" id="submission-date" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="custom_add_form form-group col-12">
                            <label for="assignment-file" class="bmd-label-floating file-label">Document
                                Attachment</label>
                            <input type="file" name="assignment-file" id="assignment-file"
                                onchange="updateFileLabel(this)">
                            <a href="#" class="btn btn-sm btn-primary float-right add_submit_button"
                                onclick="$('#assignment-file').trigger('click');">Select File</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Assignment text
                                    </label>
                                    <textarea name="assignment-text" id="assignment-text" class="form-control"
                                        required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">

                                <label for="subjectList" class="col-form-label col-md-12">Semester</label>
                                <select class="selectpicker custom-select1 col-md-12" id="semester" name="semester" onchange="fetchSubjects(this)">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="subjectList" class="col-form-label col-md-12">Subjects</label>
                                <select class="selectpicker custom-select1 col-md-12" id="subject" name="subject">
                                </select>
                            </div>

                        </div>

                        <div class="form-group col-12 pr-4 text-right">
                            <button class="btn btn-primary ml-2 custom-search-button" type="button"
                                onclick="addAssignment()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            if($teacherData['designation'] == 'hod' || $teacherData['designation'] == 'principal'|| $teacherData['designation'] == 'lecturer') {
                  require './includes/nav.php';
                } else {
                  require './includes/student-nav.php';
                }
            ?>
    <div class="wrapper ">
        <div class="main-panel">
            <?php
                              require_once './includes/header.php';
                                ?>
            <div class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card date-card">
                                <div
                                    class="card-header card-header-primary date-card-header d-flex justify-content-between ">
                                    <h4 class="card-title align-self-center">Assignments</h4>
                                    <?php if($teacherData['designation'] == 'hod' || $teacherData['designation'] == 'lecturer') {
                                              echo "<button class=\"btn btn-primary\" onclick=\"$('#addAssignmentModal').modal('show')\">Add New</button>";
                                            }
                                            ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion" id="accordion2">
                    </div>

                    <div class="col-md-12 align-center attendanceForm d-flex justify-content-center mt-3">
                        <button id="loadMoreButton" onclick="loadMoreAssignments()"
                            class="btn btn-primary attendanceSubmit">Show More</button>
                        <div>

                            <style>
                            .card-collapse .card-header a[aria-expanded=true] i {
                                filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
                                transform: rotate(180deg);
                            }

                            .animation-transition-fast,
                            .bootstrap-datetimepicker-widget table td>div,
                            .bootstrap-datetimepicker-widget table td span,
                            .bootstrap-datetimepicker-widget table th,
                            .bootstrap-datetimepicker-widget table th>div,
                            .bootstrap-tagsinput .tag,
                            .bootstrap-tagsinput [data-role=remove],
                            .card-collapse .card-header a i,
                            .navbar {
                                transition: all .15s ease 0s;
                            }

                            .card-collapse .card-header a i {
                                float: right;
                                /* top: 4px; */
                                position: relative;
                            }

                            .card-collapse {
                                margin-bottom: 0;
                            }

                            .accordion-body {
                                background-color: #FFF;
                            }

                            .accordion-inner {
                                padding: 20px;
                                box-shadow: 0 0px 4px rgba(0, 0, 0, 0.20);
                            }
                            </style>


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
            <script src="./assets/js/bootstrap-select.min.js"></script>
            <script src="./assets/js/plugins/bootstrap-notify.js"></script>
            <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
            <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
            <script>
            let isTeacher = false;
            $(document).ready(function() {
                $(".loader-container").css("display", "none");
            });

            </script>
            <?php   if($teacherData['designation'] == 'hod' || $teacherData['designation'] == 'lecturer') { ?>
            <script>
            
            function addAssignment() {
                let data = new FormData($("#addAssignmentForm")[0]);
                data.append("department", "<?php echo $teacherData['department'] ?>" );
                data.append("addAssignment", true);
                // console.log(data);
                $.ajax({
                    url: "data-manipulation.php",
                    type: "POST",
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // console.log(response); return;
                        $('.modal').modal('hide');
                        fetchAssignments();
                        if (response == "true") {
                            md.showNotification('top', 'center', 'Assignment Uploaded!');
                            $('.modal').modal('hide');
                        } else {
                            md.showNotification('top', 'center', 'Assignment Not Uploaded!');
                        }
                    }
                });
            }
            </script>
            <?php } ?>
            <script>
            var countLength = 2;
            var init = 0;
            var arrLength = null;

            function fetchAssignments() {
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        fetchAssignments: true
                    },
                    success: function(response) {
                        let assignments = '';
                        let assignmentArray = JSON.parse(response);
                        let arrLength = parseInt(assignmentArray.length);
                        if (countLength >= arrLength || arrLength < 3) {
                            countLength = arrLength - 1;
                        }
                        if (arrLength - countLength == 1) {
                            $('#loadMoreButton').remove();
                        }
                        for (let i = init; i <= countLength; i++) {
                            // console.log(assignmentArray);
                            assignments += `<div class="card card-collapse">
                                        <div class="card-header" role="tab" id="heading${i}">
                                          <h5 class="mb-0">
                                            <a data-toggle="collapse" href="#collapse${i}" aria-expanded="false" aria-controls="collapse${i}">
                                            Assignment No: ${assignmentArray[i][1]} (${assignmentArray[i][6].toUpperCase()})  
                                              <i class="material-icons">keyboard_arrow_down</i>
                                            </a>
                                          </h5>
                                        </div>
                                        <div id="collapse${i}" class="collapse" role="tabpanel" aria-labelledby="heading${i}" data-parent="#accordion">
                                          <div class="card-body custom_add_form">
                                            ${assignmentArray[i]['text']} <br> ${assignmentArray[i]['doc'] && `<a target='_blank' href="./filecontent/assignments/${assignmentArray[i]['doc']}"'>Download ${assignmentArray[i]['doc']}</a>` }
                                          </div>
                                        </div>
                                      </div>`;
                        }
                        $('#accordion2').html(assignments);
                    }
                });
            }
            fetchAssignments();

            function loadMoreAssignments() {
                countLength = countLength + 3;
                fetchAssignments();
            }
            </script>

            <script>
            function updateFileLabel(file) {
                $("label.file-label").text(file.files[0].name);
            }
            function fetchSubjects(semester) {
                $.ajax({
                    url: "data-manipulation.php",
                    type: "POST",
                    data: {
                        fetchSubj: true,
                        teacher: "<?php echo $teacherData['teacher_code'] ?>",
                        semester: semester.value,
                        department: "<?php echo $teacherData['department'] ?>"
                    },
                    success: function(response) {
                        $("#subject").html(response);
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }

            </script>
            <script src="assets/sidebarSript.js"></script>

</body>

</html>