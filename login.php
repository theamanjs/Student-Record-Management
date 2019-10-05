<?php
session_start();
require_once './includes/connection.php';
?>
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
  <!-- CSS Files -->
  <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
    <link rel="stylesheet" href="./assets/main_styles.css">
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
<div class="loader-container">
    <div class="loader-card">
        <div class="loader"></div>
        <div class="loader-text">Loading...</div>
    </div>
</div>
<!-- END OF LOADER -->

<div class="content">
  <div class="outer-shell">
  <div class="container-fluid">
    <div class="row align-items-center" style="height: 100%">
      <div class="col-md-4 mx-auto ">
        <div class="card ">
          <div class="card-header card-header-primary">
              <h4 class="card-title">Login</h4>
          </div>
            <div class="card-body">
              <form method="POST" id="loginInfo">
                <div class="row justify-content-center mar-row">
                      
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Username</label>
                          <input type="text" class="form-control" name="Username" required autofocus>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Password</label>
                          <input type="password" class="form-control" name="Password" id="showPassword" required>
                          <i onclick="showPassword('showPassword',this)" class="material-icons show-eye password-eye">remove_red_eye</i>
                        </div>
                      </div>



                    <button class="btn btn-primary loginButton" type="submit">Login</button>
                    <div class="clearfix"></div>
                  </div>
                  </form>
                </div>
              </div>

          </div>
            
        </div>
    </div>
  </div>

</div>








      <!--   Core JS Files   -->
  <script src="./assets/js/core/jquery.min.js"></script>
  <script src="./assets/js/core/popper.min.js"></script>
  <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

  <!--  Notifications Plugin    -->
  <script src="./assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>

 <script src="assets/sidebarSript.js">
  </script>

    <script>

  function showPassword(id,eye){
    var x=document.getElementById(id);
    if(x.type==="password"){
      x.type="text";
    }
    else{
      x.type="password";
    }
    $( eye ).toggleClass( "highlight-eye" );
  }
  </script>


<script type="text/javascript">
$("#loginInfo").on("submit",function(e){
  e['originalEvent'].preventDefault();
  login();
})


  function login(){
    $(".loader-container").css("display","flex");
    let data = new FormData(document.getElementById('loginInfo'));
    data.append('loginSubmit', true);
    $.ajax({
      type:"POST",
      url:"data-manipulation.php",
      data,
      processData: false,
      contentType: false,
      success: function(response){
        if(response != "false"){
          window.location.href = response;
        }
        else{
          md.showNotification('top','center','Wrong Username or Password!');
          $(".loader-container").css("display","none");
        }
      }
    });
  }
</script>

</body>
</html>