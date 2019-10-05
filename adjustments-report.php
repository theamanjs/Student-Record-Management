<?php

    require_once './includes/connection.php';
    require_once './includes/authentication.php';
    $query = "SELECT * FROM teacher_list WHERE teacher_username='" . $_SESSION['Username'] . "'";
    $teacherData = $conn->query($query)->fetch_array();
    if($_SESSION['userType'] == "Student") {
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
                Attendance GNDPC
            </title>
            <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
            <!--     Fonts and icons     -->
            <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
            <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
            <link rel="stylesheet" href="./assets/main_styles.css">
            <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">

            <!-- CSS Files -->
            <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
            <link rel="stylesheet" type="text/css" href="assets/css/daterangepicker.css" />
            <style>

.custom-datepicker{
  background: #fff !important;
  cursor: pointer !important;
  padding: 9px 10px !important;
  border: 2px solid var(--themeColorDark) !important;
  width: 100% !important;
  border-radius:4px;
  margin-top:4px;
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



<div class="modal" id="report-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header custom_modal_header">
                        <h5 class="modal-title custom_modal_title">Attendance Report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-container mt-4" style="position:relative;">



                        </div>
                    <div class="row justify-content-center mb-4 mx-sm-3 custom_add_form">
                <button id="export_report_xlsx" class="btn btn-primary col-md-3 add_submit_button">Export Excel</button>
                <div class="col-md-1"></div>
                <button id="" class="btn btn-primary col-md-3 add_submit_button">Export PDF</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>




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
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card ">
                                                <div class="card-header card-header-primary ">
                                                <h4 class="card-title">Attendance Report</h4>
                                                </div>

                                                <div class="card-body">
                                                
                                                    <form id="" class="custom_add_form">
                                                    <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">Select Date range
                                                                </label>
                                                                <div id="reportrange" class="custom-datepicker">
                                                                <i class="fa fa-calendar"></i>&nbsp;
                                                                <span></span> <i class="fa fa-caret-down" style="float: right; margin-top: 4px;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="semester">Semester
                                                                </label>
                                                                <select class="selectpicker custom-select1 col-md-12" id="semester" name="" >
                                                                    <?php
                                                                         for ($i = 1; $i <= 6; $i++) {
                                                                         echo "<option>" . $i . "</option>";
                                                                             }
                                                                         ?>
                                                                   </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-primary add_submit_button" data-toggle="modal" data-target="#report-modal" onclick="fetchReport()">Submit
                                                    </button>
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
                <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
                <script src="assets/js/core/bootstrap-select.js"></script>
                
                
                <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
                <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

                <!--Table Export Excel-->
                <script src="assets/js/tableExport/xlsx.core.min.js"></script>
                <script src="assets/js/tableExport/FileSaver.min.js"></script>
                <script src="assets/js/tableExport/tableexport.min.js"></script>

                <script>
                    $(document).ready(function() {
                      $(".loader-container").css("display","none");
                     
                    });

                    function takeAttendance(el) {
                        window.location.href = "attendance.php?lecture=" + el.getAttribute("data-lecture") + "&sections=" + el.getAttribute("data-section") + "&department=" + el.getAttribute("data-department") + "&semester=" + el.getAttribute("data-semester") + "&subjectInitials=" + el.getAttribute("data-subject-initials") + "&subject=" + encodeURIComponent(el.getAttribute("data-subject")) + "&subjectCode=" + el.getAttribute("data-subject-code");
                    }

         </script>


<script>
function triggerExportExcelButton(){
    var tableId = 'report-table';
    var buttonId = 'export_report_xlsx';
    var ExportButtons = document.getElementById(tableId);
    var instance = new TableExport(ExportButtons, {
        formats: ['xlsx'],
        headers: true,
        footers: true,
        filename: 'id',
        sheetname: 'Sheet1',
        bootstrap: false,
        position: 'bottom',
        exportButtons: true,
        ignoreRows: null,
        ignoreCols: null,
        ignoreCSS: '.table-ignore',
        emptyCSS: '.table-empty',
        trimWhitespace: true
    });
    // var XLSX = instance.CONSTANTS.FORMAT.XLSX;
    // //                                          // "id"  // format
    // var exportDataXLSX = instance.getExportData()[tableId][XLSX];
    // // get filesize
    // var bytesXLSX = instance.getFileSize(exportDataXLSX.data, exportDataXLSX.fileExtension);
    // // console.log('filesize (XLSX):', bytesXLSX + 'B');
    // var XLSXbutton = document.getElementById(buttonId);
    // XLSXbutton.addEventListener('click', function (e) {
    //     //                   // data             // mime                 // name                 // extension
    //     instance.export2file(exportDataXLSX.data, exportDataXLSX.mimeType, exportDataXLSX.filename, exportDataXLSX.fileExtension);
    // });
}
</script>







         <script type="text/javascript">
    let startDate, endDate;
$(function() {
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        startDate = start._d;
        endDate = end._d;
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});



function fetchReport() {
    
    startDate =  startDate.getFullYear()  + "-" + (startDate.getMonth()+1) + "-" + startDate.getDate();
    endDate =  endDate.getFullYear()  + "-" + (endDate.getMonth()+1) + "-" + endDate.getDate();
    console.log(startDate, endDate);
    $.ajax({
        type: "POST",
        url: "data-manipulation.php",
        data: {
            fetchReport: true,
            semester: $("#semester").val(),
            department: "<?php echo $teacherData['department']; ?>",
            startDate:startDate,
            endDate:endDate
        },
        success: function(response) {
            $('.table-container').html(response);
            triggerExportExcelButton();
        }
    });
}

</script>

                <script src="assets/sidebarSript.js"></script>
<script>
setTimeout(function() {
    document.querySelector('.cancelBtn').classList.remove('btn-default');
},100);
</script>











        </body>

        </html>