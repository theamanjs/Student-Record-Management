<?php
require_once './includes/connection.php';
require_once './includes/authentication.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Adjustments - GNDPC
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
  <!-- CSS Files -->
  <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="./assets/main_styles.css">
  <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
 
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

<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header custom_modal_header">
        <h5 class="modal-title custom_modal_title">Assign Teacher</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form class="col row">
        <div class="form-group col-12">
            <label for="newTeacher" class="col-form-label">Teacher</label>
            <select id="newTeacher" name="newTeacher" class="selectpicker custom-select1 col-12">
                
            </select>
        </div>
        <div class="form-group col-12 pr-4 text-right">
            <button class="btn btn-success ml-2 custom-search-button" type="button" onclick="removeTeacher()" id="removeButton">Remove</button>
            <button class="btn btn-primary ml-2 custom-search-button" type="button" onclick="assignTeacher()">Assign</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

  <div class="wrapper">
    <?php
    
  require './includes/nav.php';
    
    ?>
    <div class="main-panel">
        <?php

  require './includes/header.php';
    
    ?>
      <div class="content">
        <div class="">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title">Adjustments</h4>
              <p class="card-category">Select the teacher.</p>
            </div>
            <div class="card-body">
            <form class="col row">

            <div class="form-group col-md-6 text-left">
                <label for="teacher" class="col-form-label col-md-6">Teacher</label>
                <select id="teacher" name="teacher" class="selectpicker custom-select1 col-md-12" onchange="fetchLectures()">
                    <?php
                    $query = "SELECT * FROM teacher_list WHERE department='" . $teacherData['department'] . "'";
                    $result = $conn->query($query);
                    $counter = 0;
                    while($row = $result->fetch_array()) {
                        if($counter == 0) $selected = 'selected';
                        else $selected = '';
                        echo "<option value='" . $row['teacher_code'] . "' $selected>" . $row['name'] . " (" . $row['initials'] . ")</option>";
                        $counter++;
                    }
                    ?>
                </select>
            </div>
            </form>
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead class="text-primary">
                                <tr>
                                    <th>Lecture</th>
                                    <th>Subject</th>
                                    <th>Semester</th>
                                    <th>Section</th>
                                    <th>Assigned Teacher</th>
                                </tr>
                            </thead>
                            <tbody class='lecture-container'>
                            
                            </tbody>
                        </table>
                    </div>
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
    
  </div>

  <!--   Core JS Files   -->
  <script src="./assets/js/core/jquery.min.js"></script>
  <script src="./assets/js/core/popper.min.js"></script>
  <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="./assets/js/bootstrap-select.min.js"></script>
  <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
   <script src="assets/sidebarSript.js"></script>
  <script>
  let temp;
  function fetchLectures() {
    $.ajax({
        type: "POST",
        url: "data-manipulation.php",
        data: {
            fetchLectures: true,
            teacherCode: $("#teacher").val(),
            department: "<?php echo $teacherData['department']; ?>"
        },
        success: function (response) {
            let data = JSON.parse(response);
            let timetable = '';
            for(let i = 1; i <= 7; i++) {
                timetable += `<tr><th>${i}</th>`;
                if(data[i] !== undefined) {
                    let type = data[i]['type'] == 'Theory' ? 'L' : 'P';
                    let onClick = '';
                    timetable += `<td class='${(data[i]['assigned'] != undefined) ? 'font-weight-bold text-primary' : ''}'>${data[i]['subject']} (${type})</td><td>${data[i]['semester']}</td><td>${data[i]['section']}</td><td onclick="showTeacherList('${data[i]['subjectCode']}', '${data[i]['type']}', '${data[i]['semester']}', '${data[i]['section']}', '${i}', this.innerText)">${(data[i]['assignedTeacher'] != undefined) ? data[i]['assignedTeacher']  : (data[i]['assigned'] != undefined) ? '<span class="font-italic text-primary">ASSIGNED!</span>' : '---'}</td>`;
                } else {
                    timetable += `<td></td><td></td><td></td><td></td>`;
                }
                timetable += `<tr>`;
            }
            $(".lecture-container").html(timetable);
        }
    });
  }

    function showTeacherList(subject, type, semester, section, lecture, text) {
    if(text == 'ASSIGNED!') return false;
    temp = {subject, type, semester, section, lecture};
    $('.modal').modal('show');
    if(text != '---') {
        $('#removeButton').css('display', 'inline-block');
    } else {
        $('#removeButton').css('display', 'none');
    }
    $.ajax({
        type: "POST",
        url: "data-manipulation.php",
        data: {
            fetchFreeTeachers: true,
            semester: 6,
            lecture,
            department: "<?php echo $teacherData['department']; ?>"
        },
        success: function(response) {
            let teacherList = "<optgroup label='Available'>";
            let data = JSON.parse(response);
            for(let i in data) {
                if(data[i]['free'] == true) {
                    teacherList += `<option value="${data[i]['code']}">${data[i]['name']} (${data[i]['initials']})</option>`;
                }
            }
            teacherList += `</optgroup><optgroup label='Busy'>`;
            for(let i in data) {
                if(data[i]['free'] == false) {
                    teacherList += `<option value="${data[i]['code']}">${data[i]['name']} (${data[i]['initials']})</option>`;
                }
            }
            teacherList += `</optgroup>`;
            $("#newTeacher").html(teacherList);
            $('.selectpicker').selectpicker('refresh');
        }
    });
    }

    function assignTeacher() {
        $.ajax({
            type: "POST",
            url: "data-manipulation.php",
            data: {
                teacherCode: $("#teacher").val(),
                assignedTeacher: $("#newTeacher").val(),
                subject: temp.subject,
                type: temp.type,
                semester: temp.semester,
                section: temp.section,
                lecture: temp.lecture,
                department: "<?php echo $teacherData['department']; ?>",
                assignTeacher: true
            },
            success: function(response) {
                $('.modal').modal('hide');
                fetchLectures();
            }
        });
    }

    function removeTeacher() {
        $.ajax({
            type: "POST",
            url: "data-manipulation.php",
            data: {
                teacherCode: $("#teacher").val(),
                subject: temp.subject,
                semester: temp.semester,
                lecture: temp.lecture,
                department: "<?php echo $teacherData['department']; ?>",
                removeAssignedTeacher: true
            },
            success: function(response) {
                $('.modal').modal('hide');
                fetchLectures();
            }
        });
    }

  fetchLectures();
  </script>
</body>

</html>
