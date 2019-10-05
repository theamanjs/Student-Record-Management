<?php
    require_once './includes/connection.php';
    require_once './includes/authentication.php';
    if($_SESSION['userType'] == "lecturer" || $_SESSION['userType'] == "principal" || $_SESSION['userType'] == 'hod'){
        header("Location: dashboard.php");
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="./assets/img/favicon.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Student Attendance - GNDPC</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />

        <!-- CSS Files -->
        <link rel="stylesheet" href="./assets/main_styles.css">
        <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />

        <style>
            .attendance-td{
                cursor:default !important;
            }
        </style>
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

        <div class="modal lectures-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title "></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('.attendance-modal').modal('show')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-container mt-4" style="position:relative;max-height:70vh!important;">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wrapper ">
            <?php
              require './includes/student-nav.php';
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

        <!-- JS Files -->
        <script src="./assets/js/core/jquery.min.js"></script>
        <script src="./assets/js/core/popper.min.js"></script>
        <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
        <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
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

        <script>
            function showAttendance(dateSelected, fullDate) {
                $('.loader-container').css('display', 'flex');
                $(".attendance-modal").modal("show");
                $('.attendance-modal.show').perfectScrollbar();
                $('.attendance-table-container').perfectScrollbar();
                if (dateSelected)
                    $(".attendance-modal .modal-title").text(dateSelected);
                $.ajax({
                    type: 'POST',
                    url: 'data-manipulation.php',
                    data: {
                        department: "<?php echo $studentData['department']; ?>",
                        date: fullDate,
                        rollNumber: "<?php echo $studentData['roll_no']; ?>",
                        semester: "<?php echo $studentData['semester']; ?>",
                        showMyAttendance: true
                    },
                    success: function(response) {
                        let data = '';
                        let attendance = JSON.parse(response);
                        console.log(attendance);
                        data += `<div class="table-responsive"><table class="table"><tr><th>Lecture</th><th>Attendance</th>`;
                        for (let i = 1; i <= 8; i++) {
                            let wasPresent = attendance[i] == true ? 'P' : 'A';
                            data += `<tr><th>${i}</th><td class="attendance-td">${wasPresent}</td></tr>`;
                        }
                        $(".attendance-table-container").html(data);
                        $(".attendance-table-container").css('max-height','73vh');
                        $('.loader-container').css('display', 'none');
                    }
                });
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

            // To show current month calendar first
            $(".calendar-table").each((i, el) => $(el).hide());
            $(".calendar-table.current-month").show();


                    $(document).ready(function() {
                      $(".loader-container").css("display","none");
                    });
        </script>

    </body>

    </html>