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
            <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
            <!--     Fonts and icons     -->
            <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
            <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
            <link rel="stylesheet" href="./assets/main_styles.css">
            <!-- CSS Files -->
            <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
            <style>
            .date-card{
              margin-top:0 !important;
            }
              .date-card-header{
              margin:0 0 !important;
            }
            .date-card-header .btn-primary:hover, .date-card-header .btn-primary:focus{
              background-color:var(--themeColorLight) !important;
              color:#FFFFFF !important;
            }

            </style>
            <script src="./assets/js/core/jquery.min.js"></script>
<?php
include("./loader.php");
?>
        </head>

        <body class="">
<?php if($teacherData['designation'] == 'hod' || $teacherData['designation'] == 'principal') {
?>                                           
<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header custom_modal_header">
        <h5 class="modal-title custom_modal_title">Add Notice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

<form class="custom_add_form">
        <div class="row">
         <div class="col-md-12">
             <div class="form-group">
                 <label class="bmd-label-floating">Title
                     </label>
                     <input type="text" name="noticeTitle" id="noticeTitle" class="form-control" required>
                 </div>
             </div>
         </div>
        <div class="row">
         <div class="col-md-12">
             <div class="form-group">
                 <label class="bmd-label-floating">Content
                     </label>
                     <textarea type="text" name="noticeContent" id="noticeContent" class="form-control" rows="3" required></textarea>
                 </div>
             </div>
         </div>
        <div class="form-group col-12 pr-4 text-right">
            <button class="btn btn-primary ml-2 add_submit_button" type="button" onclick="addNotice()">Submit</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
}
?>
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
                                      <div class="card-header card-header-primary date-card-header d-flex justify-content-between ">
                                        <h4 class="card-title align-self-center">Notices</h4>
                                           <?php if($teacherData['designation'] == 'hod' || $teacherData['designation'] == 'principal') {
                                              echo "<button class=\"btn btn-primary\" onclick=\"$('.modal').modal('show')\">Add New</button>";
                                            }
                                            ?>
                                      </div>
                                    </div>
                                  </div>
                                </div>


<div id="accordion" role="tablist">

</div>

<div class="col-md-12 align-center attendanceForm d-flex justify-content-center">
<button id="loadMoreButton" onclick="loadMoreNotices()" class="btn btn-primary attendanceSubmit">Show More</button>
</div>


<style>
.card-collapse .card-header a[aria-expanded=true] i {
    filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
    transform: rotate(180deg);
}
.animation-transition-fast, .bootstrap-datetimepicker-widget table td>div, .bootstrap-datetimepicker-widget table td span, .bootstrap-datetimepicker-widget table th, .bootstrap-datetimepicker-widget table th>div, .bootstrap-tagsinput .tag, .bootstrap-tagsinput [data-role=remove], .card-collapse .card-header a i, .navbar {
    transition: all .15s ease 0s;
}
.card-collapse .card-header a i {
    float: right;
    /* top: 4px; */
    position: relative;
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
                <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
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
            <script>
<?php if($teacherData['designation'] == 'hod' || $teacherData['designation'] == 'principal') {
?>  
            function addNotice() {
                $.ajax({
                  type: "POST",
                  url: "data-manipulation.php",
                  data: {
                    noticeTitle: $("#noticeTitle").val(),
                    noticeContent: $("#noticeContent").val(),
                    level:"<?php echo $teacherData['designation']; ?>",
                    department: "<?php echo $teacherData['department']; ?>",
                    addNotice: true
                  },
                  success: function(response) {
                    $('.modal').modal('hide');
                    fetchNotices();
                    if (response == "true") {
                            md.showNotification('top', 'center', 'Notice Uploaded!');
                            $('.modal').modal('hide');
                        } else {
                            md.showNotification('top', 'center', 'Notice Not Uploaded!');
                    }
                  }
                });
              $("#noticeTitle").val('');
              $("#noticeContent").val('');
            }
            <?php
              }
            ?>
            var countLength=2;
            var init=0;
            var arrLength=null;
            function fetchNotices() {
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data: {
                        fetchNotices: true
                    },
                    success: function (response) {
                        let notices='';
                        let noticeArray = JSON.parse(response);
                        let arrLength=parseInt(noticeArray.length);
                        if(countLength>=arrLength || arrLength < 3){countLength=arrLength-1;}
                        if(arrLength-countLength==1){$('#loadMoreButton').remove();}
                        for(let i = init; i <= countLength; i++) {
                            notices += `<div class="card card-collapse">
                                        <div class="card-header" role="tab" id="heading${i}">
                                          <h5 class="mb-0">
                                            <a data-toggle="collapse" href="#collapse${i}" aria-expanded="false" aria-controls="collapse${i}">
                                              ${noticeArray[i][2]} (${noticeArray[i][1]})
                                              <i class="material-icons">keyboard_arrow_down</i>
                                            </a>
                                          </h5>
                                        </div>
                                        <div id="collapse${i}" class="collapse" role="tabpanel" aria-labelledby="heading${i}" data-parent="#accordion">
                                          <div class="card-body">
                                            ${noticeArray[i][3]}
                                          </div>
                                        </div>
                                      </div>`;
                        }
                        $('#accordion').html(notices);
                    }
                });
            }
            fetchNotices();
            function loadMoreNotices(){
              countLength=countLength+3;
              fetchNotices();
            }
            </script>
        </body>

        </html>