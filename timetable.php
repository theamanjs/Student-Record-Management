<?php
require_once './includes/connection.php';
require_once './includes/authentication.php';
if ($_SESSION['userType'] == 'Student') {
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
            Timetable - GNDPC
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
        <link rel="stylesheet" href="./assets/main_styles.css">
        <!-- CSS Files -->
        <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">
        <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />


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
<div class="loader-container" style="display:flex;">
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
                            <div class="modal-dialog" role="document" style="min-width: 700px;">
                                <div class="modal-content">
                                    <div class="modal-header custom_modal_header">
                                        <h5 class="modal-title custom_modal_title">Edit Entry</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i class="material-icons">
                                            close
                                            </i>
                                        </button>
                                    </div>
                                    <div class="modal-body row">
                                        <div class=" col-md-12 text-center infoAboutEntry">
                                        </div>
                                        <input type="hidden" id="day">
                                        <input type="hidden" id="lecture">
                                        <div class="row col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="subjectList" class="col-form-label col-md-12">Subjects</label>
                                                <select id="subjectList" name="subjectList" class="col-md-12 selectpicker custom-select1" required size=10 onfocus="getInitialOld()" onchange="setDefaultTeacher()">
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="teacherList" class="col-form-label col-md-12">Teachers</label>
                                                <select id="teacherList" name="teacherList" class="col-md-12 selectpicker custom-select1" required multiple size=10>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 pl-3 text-right">
                                            <button class="btn btn-success copy-btn" onclick="copyLastEntry()">Copy Last Lecture</button>
                                            <button class="btn btn-primary save-btn" onclick="updateEntry()">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="card-header card-header-primary">
                                        <h4 class="card-title">Time Table</h4>
                                        <?php if($_SESSION['userType'] == 'hod')
                                        echo '<p class="card-category">Double click to update attendance</p>';
                                        ?>
                                    </div>
                                    <div class="card-body">

                                        <form class="col row mb-3">
                                            <div class="form-group col-md-6 text-left">
                                                <label for="semester" class="col-form-label">Semester</label>
                                                <select id="semester" name="semester" class="selectpicker custom-select1 col-md-12" onchange='showTimetable()'>
                                                    <?php
for ($i = 1; $i <= 5; $i++) {
    echo "<option>" . $i . "</option>";
}
?>
                                                        <option value="6" selected>6</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6 text-left">
                                                <label for="section" class="col-form-label">Section</label>
                                                <select id="section" name="section" class="selectpicker custom-select1 col-md-12" onchange='showTimetable()'>
                                                    <?php
$query = "SELECT sec FROM departments WHERE initials='" . $teacherData['department'] . "'";
$sections = explode(',', $conn->query($query)->fetch_array()[0]);
foreach ($sections as $sec) {
    echo "<option>{$sec}</option>";
}
?>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="table-responsive ">
                                            <table class="table table-bordered custom_timetable">
                                                <thead class="text-center tableHead">
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
                                                    <tr></tr>
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
        <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
        <script src="assets/sidebarSript.js"></script>
        <?php
            echo '<script>var typeOfTeacher = "'.$_SESSION['userType'].'";</script>'
        ?>
        <script>
            let timetable;
            let subjectInitialForOld;
            let subjectInitialForNew;
            let days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            
            function showTimetable(flag = false) {
                $(".loader-container").css("display", "flex");
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        semester: $("#semester").val(),
                        section: $("#section").val(),
                        department: "<?php echo $teacherData['department']; ?>",
                        showTimetable: true
                    },
                    success: function(response) {
                        $(".loader-container").css("display", "none");
                        timetable = JSON.parse(response);
                        // console.log(timetable);
                        if (timetable.length !== 0) {
                            let data = '';
                            let type = '';
                            for (let i = 1; i <= 8; i++) {
                                data += `<tr><th>${i}</th>`;
                                for (let day in timetable) {
                                    if (timetable[day][i]['type'] == "Theory")
                                        type = "L";
                                    else
                                        type = "P";
                                        if(typeOfTeacher == 'hod')
                                    data += `<td ondblclick="editTimetable(this.attributes['data-day'].value, this.attributes['data-lecture'].value, this.attributes['data-subject'].value)" data-day="${day}" data-lecture="${i}" data-subject='${timetable[day][i]['subject']}' data-teachers='${timetable[day][i]['teachers']}'><span style="display:block">${timetable[day][i]['subject']}</span><span style="display:block">${timetable[day][i]['teachers']}</span><span style="display:block">${type}</span></td>`;
                                    else 
                                    data += `<td data-day="${day}" data-lecture="${i}" data-subject='${timetable[day][i]['subject']}' data-teachers='${timetable[day][i]['teachers']}'><span style="display:block">${timetable[day][i]['subject']}</span><span style="display:block">${timetable[day][i]['teachers']}</span><span style="display:block">${type}</span></td>`;
                                    
                                }
                                data += `<th>${i}</th>`;
                                data += '</tr>';
                            }
                            $("#timetable").html(data);
                            if (flag) {
                                let day = $("#day").val();
                                let lecture = $("#lecture").val();
                                let subjectName = timetable[day][lecture]['subject'];
                                editTimetable(day, lecture, subjectName);
                            }
                        }
                    }
                });
            }

            function editTimetable(entryDay, entryLecture, entrySubject) {
                $(".loader-container").css("display", "flex");
                $(".infoAboutEntry").html(`
    <div class="row justify-content-center">
    <div class="btn-group" role="group" aria-label="First group">
      <button type="button" class="btn btn-secondary toggle-section ${$("#section").val() == "<?php echo $sections[0]; ?>" ? "active":""}" onclick='changeSection(0)'><?php echo $sections[0]; ?></button>
      <button type="button" class="btn btn-secondary toggle-section ${$("#section").val() == "<?php echo $sections[1]; ?>" ? "active":""}" onclick='changeSection(1)'><?php echo $sections[1]; ?></button>
    </div>
    </div>
    <div class="row offset-md-1">
    <div class="btn-group col-md-5 justify-content-center" role="group" aria-label="First group">
      <button type="button" class="btn btn-secondary dayA" onclick="changeDay(-1)">-
      </button>
      <button type="button" class="btn btn-secondary customWidth">${days[entryDay - 1]}</button>
      <button type="button" class="btn btn-secondary dayA" onclick="changeDay(1)">+
      </button>
    </div>
    <div class="btn-group col-md-6 justify-content-center" role="group" aria-label="First group">
      <button type="button" class="btn btn-secondary lectureA" onclick="changeLecture(-1)">-</button>
      <button type="button" class="btn btn-secondary customWidth">Lecture ${entryLecture}</button>
      <button type="button" class="btn btn-secondary lectureA" onclick="changeLecture(1)">+</button>
  </div>
  </div>`);
                $("#day").val(entryDay);
                $("#lecture").val(entryLecture);
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        semester: $("#semester").val(),
                        department: "<?php echo $teacherData['department']; ?>",
                        fetchTeachers: true
                    },
                    success: function(response) {
                        $(".loader-container").css("display", "none");
                        $("#teacherList").html(response);
                        $.ajax({
                            type: "POST",
                            url: "data-manipulation.php",
                            data: {
                                semester: $("#semester").val(),
                                department: "<?php echo $teacherData['department']; ?>",
                                fetchSubjects: true
                            },
                            success: function(response) {
                                console.log(response);
                                $("#subjectList").html(response);
                                for (let option in $("#subjectList")[0].options) {
                                    if (option != "length") {
                                        if ($("#subjectList").prop('options')[option].innerHTML == entrySubject)
                                            $("#subjectList")[0].options.selectedIndex = option;
                                    }
                                }
                                $(".modal").modal("show");
                                setDefaultTeacher();
                                $(".selectpicker").selectpicker("refresh");
                                subjectInitialForOld = $("#subjectList")[0].selectedOptions[0].innerText;
                            }
                        });
                    }
                });
                $(".copy-btn").html("Copy Last Lecture");
            }

            function updateEntry() {
                let data = $("#subjectList").val() + ":";
                subjectInitialForNew = $("#subjectList")[0].selectedOptions[0].innerText;
                let subjectType = $("#subjectList")[0].selectedOptions[0].getAttribute("subject-type");
                for (let i = 0; i < $("#teacherList")[0].selectedOptions.length; i++) {
                    data += $("#teacherList")[0].selectedOptions[i].value + ",";
                }
                data = data.slice(0, -1);
                // console.log($("#lecture").val());
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        data,
                        day: $("#day").val(),
                            lecture: $("#lecture").val(),
                            semester: $("#semester").val(),
                            section: $("#section").val(),
                            department: "<?php echo $teacherData['department']; ?>",
                            subjectType,
                            subjectInitialForOld,
                            subjectInitialForNew,
                            updateEntry: true
                    },
                    success: function(response) {
                        console.log(response);
                        $(".modal").modal("hide");
                        showTimetable();
                    }
                });
            }

            function setDefaultTeacher() {
                if( $("#subjectList")[0].selectedOptions.length === 0 ) return;
                let teacherCode = $("#subjectList")[0].selectedOptions[0].getAttribute("teacher-code");
                for (let i = 0; i < $("#teacherList")[0].length; i++) {
                    if (teacherCode == $("#teacherList")[0].options[i].value) {
                        $("#teacherList")[0].selectedIndex = i;
                        $(".selectpicker").selectpicker("refresh");
                        break;
                    }
                }
            }

            function copyLastEntry() {
                let day = $("#day").val();
                let lecture = $("#lecture").val() - 1;
                if (lecture < 1)
                    return false;
                let subjectName = timetable[day][lecture]['subject'];
                editTimetable(day, lecture, subjectName);
                $("#lecture").val(lecture + 1);
                $(".copy-btn").html("Copied!");
            }

            function changeDay(value) {
                let day = parseInt($("#day").val()) + value;
                if (day < 1 || day > 5)
                    return false;
                $("#day").val(day);
                let lecture = $("#lecture").val();
                let subjectName = timetable[day][lecture]['subject'];
                editTimetable(day, lecture, subjectName);
            }

            function changeLecture(value) {
                let lecture = parseInt($("#lecture").val()) + value;
                if (lecture < 1 || lecture > 8)
                    return false;
                let day = $("#day").val();
                $("#lecture").val(lecture);
                let subjectName = timetable[day][lecture]['subject'];
                editTimetable(day, lecture, subjectName);
            }

            function changeSection(value) {
                $("#section").prop("selectedIndex", value);
                showTimetable(true);
            }

            $(document).ready(function() {
                $('.inner.show').perfectScrollbar();
            });

            showTimetable();


                    $(document).ready(function() {
                      $(".loader-container").css("display","none");
                    });
        </script>

    </body>

    </html>