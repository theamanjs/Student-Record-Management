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
            View Attendance - GNDPC
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
        <link rel="stylesheet" href="./assets/css/font-awesome.min.css">           
         <link rel="stylesheet" href="./assets/main_styles.css">

        <!-- CSS Files -->
        <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
<style>

.active-link-toggle2{
    color:#FFF !important;
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

        <div class="modal lectures-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header custom_modal_header">
                        <h5 class="modal-title custom_modal_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('.attendance-modal').modal('show')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-container mt-4" style="position:relative; max-height:70vh;">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wrapper ">
            <?php
              require './includes/nav.php';
                ?>
                <div class="main-panel">
                    <?php
                      require './includes/header.php';
                        ?>
                        <div class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header card-header-primary">
                                                <h4 class="card-title ">Calender</h4>
                                                <p class="card-category">Select date to view attendance</p>
                                            </div>
                                            <div class="card-body">
                                                <div class="modal attendance-modal" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header m-head custom_modal_header">
                                                                <h5 class="modal-title custom_modal_title">Attendance</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons">
close
</i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul class="nav nav-tabs">
                                                                    <?php
            $query = "SELECT sec FROM departments WHERE initials='" . $teacherData['department'] . "'";
            $sections = explode(",", $conn->query($query)->fetch_array()[0]);
            if(date('n') <= 6) {
                $semesters = array(2,4,6);
                echo "<script> let semesters = [2,4,6]; </script>";
            } else {
                $semesters = array(1,3,5);
                echo "<script> let semesters = [1,3,5]; </script>";
            }
            foreach($semesters as $key => $value) {
                echo "<li class='nav-item my-nav-item'>
                        <a class='nav-link my-nav-link' href='javascript:showTable({$key})'>Semester {$value}</a>
                    </li>";
            }
            ?>
              </ul>
                <div class="attendance-table-container mt-4" style="position:relative; max-height:60vh;">
                  </div>
                     </div>
                         </div>
                       </div>
                    </div>
        <!-- Calendar -->
    <div class="card col-md-8 p-0  mx-auto mt-5">
    <?php
        $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $currentYear = date('Y'); // Example: 2017, 2018
        $currentMonth = date('n'); // Example: 1, 2, 3 ..., 12
        if($currentMonth <= 6)
            $firstMonth = 1; // means January
        else
            $firstMonth = 7; // means July
        for($i = $firstMonth; $i <= $firstMonth + 5; $i++ ) { // for iterating months in semester
            if($i == $currentMonth)
                echo "<div class='table-responsive calendar-table current-month'><table class='table table-bordered calender-table'><tr>";
            else
                echo "<div class='table-responsive calendar-table'><table class='table table-bordered'><tr>";
            echo "<th class='arrow-btn cal-td' onclick='changeMonth(-1, {$i})'><i class='material-icons v-align '>chevron_left</i><th class='cal-td' colspan=5>" . strtoupper($months[$i - 1]) . "</th><th class='arrow-btn cal-td' onclick='changeMonth(1, {$i})'><i class='material-icons v-align '>chevron_right</i></th><tr>";
            array_map(function($day) {
                echo "<th class='cal-td'>{$day}</th>";
            }, $days);
            echo '</tr>';
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $i, $currentYear);
            for($j = 1, $k = 1; $j <= $daysInMonth; $j++, $k++ ) { // for iterating days in the month
                if($k === 1) echo '<tr >';
                $date = $currentYear . '-' . $i . '-' . $j;
                $day = date('D', strtotime($date)); // finds day from a date e.g. Mon, Tue,...
                if($day == $days[$k - 1]) {
                   if($day == "Sun" || $day == "Sat") {
                                                                        echo "<td> {$j} </td>";
                                                                    } else {
                                                                        if($date == date("Y-n-j")) {
                                                                            echo "<td class='cal-td cal-hover current-date' onclick='showAttendance(\"". date('j F', strtotime($date)) . " (" . date('l', strtotime($date)) . ")\", \"" . $date . "\")'> {$j} </td>";
                                                                        } else {
                                                                            echo "<td class='cal-td cal-hover' onclick='showAttendance(\"". date('j F', strtotime($date)) . " (" . date('l', strtotime($date)) . ")\", \"" . $date . "\")'> {$j} </td>";
                                                                        }
                                                                    }
                }
                else {
                    echo'<td class="cal-td"></td>';
                    $j--;
                    continue;
                }
                if($k === 7) { // to break the row when 7 days are filled
                    echo '</tr>';
                    $k = 0;
                }
                if($j == $daysInMonth && $k < 7) { // to create empty cells after the calendar is filled
                    while ($k < 7) {
                        echo '<td class="cal-td"></td>';
                        $k++;
                    }
                }
            }
            echo "</tr></table></div>";
        }
        ?>
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

        <!--   Core JS Files   -->

        <script src="./assets/js/core/jquery.min.js"></script>
        <script src="./assets/js/core/popper.min.js"></script>
        <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
        <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>


        <script>
            function preventDefault(e) {
                e = e || window.event;
                if (e.preventDefault)
                    e.preventDefault();
                e.returnValue = false;
            }
            window.addEventListener('DOMMouseScroll', preventDefault, false);
            window.onwheel = preventDefault;
        </script>
        <?php
        echo '<script>var typeOfTeacher = "'.$_SESSION['userType'].'";</script>'
        ?>
        <script>
            function showAttendance(dateSelected, fullDate) {
                $(".attendance-modal").modal("show");
                $('.attendance-modal.show').perfectScrollbar();
                $('.attendance-table-container').perfectScrollbar();
                if (dateSelected)
                    $(".attendance-modal .modal-title").text(dateSelected);
                $.ajax({
                    type: 'POST',
                    url: 'data-manipulation.php',
                    data: {
                        department: "<?php echo $teacherData['department']; ?>",
                        date: fullDate,
                        showAttendance: true
                    },
                    success: function(response) {
                        // $(".modal-body .attendance-table").each((i, el) => el.remove());
                        let data = '';
                        let attendance = JSON.parse(response);
                        for (let semester in semesters) {
                            data += `<div class="table-responsive attendance-table">
                <table class="table table-bordered table-sm atten-table">`
                            if (semesters[semester] in attendance) {
                                data += `<tr><th></th>`;
                                for (let i = 1; i <= 8; i++) {
                                    data += `<td>${i}</td>`;
                                }
                                data += `</tr>`;
                                for (let student in attendance[semesters[semester]]) {
                                    data += `<tr><td class="font-weight-bold" onclick="showLecturesCount(${student}, ${semesters[semester]})">${student}</td>`;
                        for(let i=1; i<=8; i++) {
                            if(attendance[semesters[semester]][student][i] == null)
                                data += `<td class='null-data'></td>`;
                            else if(attendance[semesters[semester]][student][i] === 1)
                                data += `<td ondblclick="updateAttendance('${fullDate}','${student}', ${i}, ${semesters[semester]}, 1, this)" class='text-success font-weight-bold'>P</td>`;
                            else
                                data += `<td ondblclick="updateAttendance('${fullDate}','${student}', ${i}, ${semesters[semester]}, 0, this)" class='text-danger'>-</td>`;
                        }
                        data += '</tr>';
                                }
                            } else {
                                data += '<tr class="no-data-tr"><td class="no-data" style="border:0 !important"> No data found! </td></tr>';
                            }
                            data += '</table></div>';
                        }
                        $(".attendance-table-container").html(data);
                        showTable(0);
                    }
                });
            }

            function showTable(num) {
                $(".attendance-table").each((i, el) => $(el).hide());
                $(".attendance-table").eq(num).show();
                $(".my-nav-item .my-nav-link").each((i, el) => $(el).removeClass('active-link-toggle1 active-link-toggle2'));
                $(".my-nav-item").eq(num).addClass('active-link-toggle1');
                $(".my-nav-link").eq(num).addClass('active-link-toggle2');
                $('.attendance-table-container').scrollTop(0);
            }

            function changeMonth(value, currentMonth) {
                if ((value < 1) && ((currentMonth - 2) >= 0)) {
                    if ($('.calendar-table').eq(currentMonth - 2)) {
                        $('.calendar-table').eq(currentMonth - 1).hide();
                        $('.calendar-table').eq(currentMonth - 2).show();
                    }
                } else if (value === 1) {
                    if ($('.calendar-table').length > currentMonth) {
                        $('.calendar-table').eq(currentMonth - 1).hide();
                        $('.calendar-table').eq(currentMonth).show();
                    }
                }
            }

            // To Update Attendance
            function updateAttendance(date, rollNumber, lecture, semester, wasPresent, cell) {
                let section;
                if (parseInt(rollNumber.slice(-2)) >= 1 && parseInt(rollNumber.slice(-2)) <= 50) {
                    section = "<?php echo $sections[0]; ?>";
                } else {
                    section = "<?php echo $sections[1]; ?>";
                }
                $.ajax({
                    type: "POST",
                    url: 'data-manipulation.php',
                    data: {
                        updateAttendance: true,
                        date,
                        rollNumber,
                        lecture,
                        semester,
                        section,
                        department: "<?php echo $teacherData['department']; ?>",
                        wasPresent
                    },
                    success: response => {
                        if (wasPresent) {
                            if(typeOfTeacher == 'hod')
                            cell.outerHTML = `<td ondblclick="updateAttendance('${date}','${rollNumber}', ${lecture}, ${semester}, 0, this)" class='text-danger'>-</td>`;
                        } else {
                            if(typeOfTeacher == 'hod')
                            cell.outerHTML = `<td ondblclick="updateAttendance('${date}','${rollNumber}', ${lecture}, ${semester}, 1, this)" class='text-success font-weight-bold'>P</td>`;
                        }
                    }
                });
            }

            // To view the lectures of each subject with percentage
            function showLecturesCount(rollNumber, semester) {
                $(".attendance-modal").modal("hide");
                $(".lectures-modal").modal("show");
                $('.lectures-modal.show').perfectScrollbar();
                $('.lectures-modal .modal-body .table-container').perfectScrollbar();
                $('.lectures-modal .modal-body .table-container').scrollTop(0);
                $(".lectures-modal .modal-title").html("Roll Number - " + rollNumber);
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        showLecturesCount: true,
                        department: "<?php echo $teacherData['department']; ?>",
                        rollNumber,
                        semester
                    },
                    success: response => {
                        let lecturesData = "";
                        let lectures = JSON.parse(response);
                        lecturesData += `<div class="table-responsive">
                            <table class="table table-bordered lectures-table table-sm">
                                <tr><th>Total Lectures</th><td>${lectures['totalLectures']}</td></tr>
                                <tr><th>Attended Lectures</th><td>${lectures['attendedLectures']}</td></tr>
                                <tr><th>Percentage</th><td>${lectures['percentage']}%</td></tr>
                            </table>`;
                        for (let i = 0; i < lectures['length']; i++) {
                            if (i === 0) {
                                lecturesData += `<h3 class="text-center">Practical</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered lectures-table table-sm">
                        <tr><th>Subject</th><th>Total Lectures</th><th>Attended Lectures</th><th>Percentage</th>
                    </tr>`;
                            } else if (lectures[i]['type'] == "Theory" && lectures[i - 1]['type'] == "Practical") {
                                lecturesData += `<h3 class="text-center">Theory</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered lectures-table table-sm">
                        <tr><th>Subject</th><th>Total Lectures</th><th>Attended Lectures</th><th>Percentage</th>
                    </tr>`;
                            }
                            lecturesData += `<tr><th>${lectures[i]['subjectInitials']}</th><td>${lectures[i]['totalLectures']}</td><td>${lectures[i]['attendedLectures']}</td><td>${lectures[i]['percentage']}%</td></tr>`;
                            if (i < lectures['length'] - 1 && lectures[i + 1]['type'] == "Theory" && lectures[i]['type'] == "Practical") {
                                lecturesData += `</table></div>`;
                            }
                        }
                        lecturesData += `</table></div>`;
                        $(".lectures-modal .modal-body .table-container").html(lecturesData);
                    }
                });
            }

            // To set 1st semester as active by default
            $(".my-nav-item .my-nav-link").eq(0).addClass('active-link-toggle');

            // To show current month calendar first
            $(".calendar-table").each((i, el) => $(el).hide());
            $(".calendar-table.current-month").show();
        </script>
        <script src="assets/sidebarSript.js"></script>

    </body>

    </html>