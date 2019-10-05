<?php
    require_once './includes/connection.php';
    require_once './includes/authentication.php';
    if($_SESSION['userType'] == 'Student'){
        header("Location: student-dashboard.php");
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
            Personal Timetable - GNDPC
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
        <link rel="stylesheet" href="./assets/main_styles.css">
        <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">
        <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />

        <script src="./assets/js/core/jquery.min.js"></script>
        <style>
            #timetable td {
                height: 70px;
            }
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

                        <div class="modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Entry</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i class="material-icons">
close
</i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="day">
                                        <input type="hidden" id="lecture">
                                        <div class="form-group col-md-12 text-left">
                                            <label for="subjectList" class="col-form-label">Subjects</label>
                                            <select id="subjectList" name="subjectList" class="custom-select">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12 text-left">
                                            <label for="teacherList" class="col-form-label">Teachers</label>
                                            <select id="teacherList" name="teacherList" class="custom-select" multiple size=4>
                                            </select>
                                        </div>
                                        <div class="form-group-col-md-12 pl-3">
                                            <button class="btn btn-primary" onclick="updateEntry()">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="">
                                <div class="card">
                                    <div class="card-header card-header-primary">
                                        <h4 class="card-title">Time Table</h4>
                                        <p class="card-category">Personal timetable for lecturer</p>
                                    </div>
                                    <div class="card-body">

                                        <form class="col row mb-3">
                                            <div class="form-group col-md-6 text-left">
                                                <label for="departments" class="col-form-label">Departments</label>
                                                <select id="departments" name="departments" class="selectpicker custom-select1 col-md-12" onchange='fetchTeachers()'>
                                                    <?php
                                                        $query = "SELECT * FROM departments";
                                                        $result = $conn->query($query);
                                                        while($data = $result->fetch_array()){
                                                          if($data[2] == $teacherData['department'])
                                                          echo "<option value=".$data[2]." selected>" . $data[1] . "</option>";
                                                          else
                                                          echo "<option value=".$data[2].">" . $data[1] . "</option>";
                                                        }
                                                      ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6 text-left">
                                                <label for="lecturer" class="col-form-label">Lecturer</label>
                                                <select id="lecturer" name="lecturer" class="selectpicker custom-select1 col-md-12" onchange='showLecturerTimetable()'>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="table-responsive px-5 ">
                                            <table class="table table-bordered custom_timetable">
                                                <thead class="text-center tableHead">
                                                    <tr>
                                                        <th scope="col">Lectures</th>
                                                        <th scope="col">Monday</th>
                                                        <th scope="col">Tuesday</th>
                                                        <th scope="col">Wednes</th>
                                                        <th scope="col">Thursday</th>
                                                        <th scope="col">Friday</th>
                                                        <th scope="col">Lectures</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="timetable" class="text-center">
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
            let timetable;

            function fetchTeachers() {
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        semester: 4,
                        department: $("#departments").val(),
                        fetchTeachers: true
                    },
                    success: function(response) {
                        $("#lecturer").html(response);
                        for (let i = 0; i < $("#lecturer")[0].length; i++) {
                            if ($("#lecturer")[0].options[i].value == "<?php echo $teacherData['teacher_code']; ?>") {
                                $("#lecturer")[0].selectedIndex = i;
                            }
                        }
                        $('.selectpicker').selectpicker('refresh');
                        showLecturerTimetable();
                    }
                });
            }
            fetchTeachers();

            function showLecturerTimetable() {
                $(".loader-container").css("display","flex");
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        teacherCode: $("#lecturer").val(),
                        department: $("#departments").val(),
                        showLecturerTimetable: true
                    },
                    success: function(response) {
                        let data = '';
                        timetable = JSON.parse(response);
                        for (let i = 1; i <= 8; i++) {
                            data += `<tr><th>${i}</th>`; // prints lecture number
                            for (let j = 1; j <= 5; j++) {
                                if (timetable[j][i] === undefined)
                                    data += `<td></td>`; // prints empty block for a free lecture
                                else {
                                    if (timetable[j][(i - 1)] && timetable[j][i]['subject'] == timetable[j][i - 1]['subject']) {
                                        data += `<td>--</td>`; // to print symbol for continuous same lectures
                                    } else {
                                        type = (timetable[j][i]['type'] == "Theory") ? 'L' : 'P';
                                        // printing the lecture info
                                        data += `<td><span style="display:inline-block;margin-right:10px;">${type}</span><span style="display:inline-block;">${timetable[j][i]['subject']}</span><br>
                  <span style="display:inline-block;margin-right:10px;">${timetable[j][i]['semester']}</span><span style="display:inline-block">${timetable[j][i]['section']}</span></td>`;
                                    }
                                }
                            }
                            data += `<th>${i}</th></tr>`;
                        }
                        $("#timetable").html(data);
                        $(".loader-container").css("display","none");
                    }
                });
            }

            $(document).ready(function() {
                $('.inner.show').perfectScrollbar();

            });
        </script>

    </body>

    </html>