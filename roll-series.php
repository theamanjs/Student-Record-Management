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
            Detain/Retain - GNDPC
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
        <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link rel="stylesheet" href="./assets/main_styles.css">
        <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">
        <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
        <script src="./assets/js/core/jquery.min.js"></script>

        <style>

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
<div class="loader-container">
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
                        <div class="my-5">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col">
                                        <div class="card custom_rollseries_card">
                                            <div class="card-header card-header-primary">
                                                <h4 class="card-title">Roll Series</h4>
                                                <p class="card-category">Select roll numbers to detain.</p>
                                            </div>
                                            <fieldset class="form-fieldset">
                                                <div class="card-body">
                                                    <form class="col row">

                                                        <div class="form-group col-md-6 text-left">
                                                            <label for="department" class="col-form-label col-md-6">Department</label>
                                                            <select id="department" name="department" class="selectpicker custom-select1 col-md-12" onchange="fetchRollSeries()">
                                                                <?php
                                                                    $query = "SELECT * FROM departments";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_array()) {
                                                                        if ($row['initials'] !== "as") {
                                                                            echo "<option value='" . $row['initials'] . "'>" . $row['name'] . "</option>";
                                                                        }

                                                                    }
                                                                    ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-6 text-left">
                                                            <label for="semester" class="col-form-label col-md-6">Semester</label>
                                                            <select id="semester" name="semester" class="selectpicker custom-select1 col-md-12" onchange="fetchRollSeries()">
                                                                <?php
                                                                    for ($i = 1; $i <= 6; $i++) {
                                                                        echo "<option>" . $i . "</option>";
                                                                    }
                                                                    ?>
                                                            </select>
                                                        </div>

                                                        <div class="col">
                                                            <div class="row">

                                                                <div class="form-group col text-left col-md-5">
                                                                    <label for="regularStudents" class="col-form-label col-md-12">Regular Students</label>
                                                                    <select id="regularStudents" name="regularStudents" class="custom-select2 col-md-12" size="6">
                                                                    </select>
                                                                </div>

                                                                <div class="form-group col-md-2">
                                                                    <div class="row d-md-flex align-items-center mt-md-4 pt-2">
                                                                        <span class="btn btn-primary w-100 w-md-50 d-md-block d-inline-block studentButton" onclick="changeStatus('detainStudent')"> <i class="material-icons rotate-90">arrow_forward</i> </span>
                                                                        <span class="btn btn-primary w-100 w-md-50 d-md-block d-inline-block studentButton" onclick="changeStatus('retainStudent')"> <i class="material-icons rotate-90">arrow_back</i> </span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col text-left col-md-5">
                                                                    <label for="detainedStudents" class="col-form-label col-md-12">Detained Students</label>
                                                                    <select id="detainedStudents" name="detainedStudents" class="custom-select2 col-md-12" size="6">
                                                                    </select>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                          require './includes/footer.php';
                            ?>

                            <!--   Core JS Files   -->
                            <script src="./assets/js/core/jquery.min.js"></script>
                            <script src="./assets/js/core/popper.min.js"></script>
                            <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
                            <script src="./assets/js/bootstrap-select.min.js"></script>
                            <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
                            <!--  Notifications Plugin    -->
                            <script src="./assets/js/plugins/bootstrap-notify.js"></script>
                            <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
                            <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
                            <script src="assets/sidebarSript.js">
                            </script>

                            <script>
                                function fetchRollSeries() {
                                    if ($("#department").val() == null || $("#semester").val() == null)
                                        return false;
                                    $("#regularStudents").html('');
                                    $("#detainedStudents").html('');
                                    $(".loader-container").css("display", "flex");
                                    $.ajax({
                                        type: "POST",
                                        url: "data-manipulation.php",
                                        data: {
                                            fetchRollSeries: true,
                                            department: $("#department").val(),
                                            semester: $("#semester").val()
                                        },
                                        success: function(response) {
                                            let rollNumbers = JSON.parse(response);
                                            for (let key in rollNumbers) {
                                                if (rollNumbers[key]['status'] == 1) {
                                                    $("#regularStudents").append($("<option></option>").text(rollNumbers[key]['rollNumber']));
                                                } else {
                                                    $("#detainedStudents").append($("<option></option>").text(rollNumbers[key]['rollNumber']));
                                                }
                                            }
                                            $('.selectpicker').selectpicker('refresh');
                                            $(".loader-container").css("display", "none");
                                        }
                                    });
                                }

                                function changeStatus(val) {
                                    let selectList;
                                    if (val == "detainStudent") {
                                        selectList = "#regularStudents";
                                    } else {
                                        selectList = "#detainedStudents";
                                    }
                                    if ($(selectList)[0].selectedOptions.length === 0)
                                        return false;
                                    $(".loader-container").css("display", "flex");
                                    let rollNumbers = new FormData();
                                    for (let i = 0; i < $(selectList)[0].selectedOptions.length; i++) {
                                        rollNumbers.append(i, $(selectList)[0]['selectedOptions'][i].innerHTML);
                                    }
                                    rollNumbers.append(val, true);
                                    $.ajax({
                                        type: "POST",
                                        url: "data-manipulation.php",
                                        data: rollNumbers,
                                        processData: false,
                                        contentType: false,
                                        success: function(response) {
                                            fetchRollSeries();
                                            $(".loader-container").css("display", "none");
                                        }
                                    });
                                }

                                $(document).ready(function() {
                                    $('.inner.show').perfectScrollbar();
                                });

                                            $(document).ready(function() {
                                    $('.custom-select2').perfectScrollbar();
                                    });
                            </script>

    </body>

    </html>