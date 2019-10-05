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
   Attendance
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
    .card{
      margin-top: 45px !important;
    }

    .table-responsive .send-mail:active{
      background-color:var(--themeColorDark) !important;
      color:#FFF !important;
      border:0 !important;
      box-shadow:none !important;
    }
    .table-responsive .send-mail:hover{
      box-shadow:none !important;
    }
    td{
      cursor:default !important;
    }
    .card-profile .card-avatar{
      box-shadow: none !important;
    }
    .avatar-body{
      margin-top:0 !important;
    }
    .card-profile .card-avatar img{
      pointer-events:none !important;
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

            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  
                    <img class="img" src="./assets/img/4451user-img.png" />
                  
                </div>
                <div class="card-body avatar-body">
                  <h6 class="card-category text-gray">Developer and Designer</h6>
                  <h4 class="card-title">Satnam Singh</h4>
                   <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td>
                            Languages
                          </td>
                          <td>
                            JS, PHP, AJAX
                          </td>
                        </tr>
                        <tr>
                        
                        </tr>
                      </tbody>
                    </table>
                    <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=satnam9781@gmail.com&tf=1" class="btn btn-primary text-center send-mail">Send Mail</a>
                </div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <img class="img" src="./assets/img/4451user-img.png" />
                </div>
                <div class="card-body avatar-body">
                  <h6 class="card-category text-gray">Developer</h6>
                  <h4 class="card-title">Divyanshu Garg</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td>
                            Languages
                          </td>
                          <td>
                            JS, PHP
                          </td>
                        </tr>
                        <tr>
                        </tr>
                      </tbody>
                    </table>
                    <a target="_blank" href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=divyanshugarg36@gmail.com&tf=1" class="btn btn-primary text-center send-mail">Send Mail</a>
                </div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <img class="img" src="./assets/img/4451user-img.png" />
                </div>
                <div class="card-body avatar-body">
                  <h6 class="card-category text-gray">Developer</h6>
                  <h4 class="card-title">Amanjot Singh</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td>
                            Languages
                          </td>
                          <td>
                            CSS, JS ,PHP
                          </td>
                        </tr>
                        <tr>
                        </tr>
                      </tbody>
                    </table>
                    <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=amanjotsingh1599@gmail.com&tf=1" class="btn btn-primary text-center send-mail">Send Mail</a>
                </div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <img class="img" src="./assets/img/4451user-img.png" />
                </div>
                <div class="card-body avatar-body">
                  <h6 class="card-category text-gray">Developer</h6>
                  <h4 class="card-title">Kawalpreet Singh</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td>
                            Languages
                          </td>
                          <td>
                            CSS, JS
                          </td>
                        </tr>
                        <tr>
                        </tr>
                      </tbody>
                    </table>
                    <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=preetsingh7448@gmail.com&tf=1" class="btn btn-primary text-center send-mail">Send Mail</a>
                </div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <img class="img" src="./assets/img/4451user-img.png" />
                </div>
                <div class="card-body avatar-body">
                  <h6 class="card-category text-gray">Designer</h6>
                  <h4 class="card-title">Sukhmeet Singh</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td>
                            Languages
                          </td>
                          <td>
                            HTML, CSS
                          </td>
                        </tr>
                        <tr>
                        </tr>
                      </tbody>
                    </table>
                    <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=sukhsingh2729@gmail.com&tf=1" class="btn btn-primary text-center send-mail">Send Mail</a>
                </div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <img class="img" src="./assets/img/4451user-img.png" />
                </div>
                <div class="card-body avatar-body">
                  <h6 class="card-category text-gray">Designer</h6>
                  <h4 class="card-title">Himanshu Wasson</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td>
                            Languages
                          </td>
                          <td>
                            HTML, CSS
                          </td>
                        </tr>
                        <tr>
                        </tr>
                      </tbody>
                    </table>
                    <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=hcool9881@gmail.com&tf=1" class="btn btn-primary text-center send-mail">Send Mail</a>
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
        <!-- Latest compiled and minified JavaScript -->
        <script src="assets/js/core/bootstrap-select.js"></script>
        <script src="./assets/js/plugins/bootstrap-notify.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
  <script src="assets/sidebarSript.js">
  </script>
</body>

</html>
