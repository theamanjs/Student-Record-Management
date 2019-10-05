<?php
require_once './includes/connection.php';
require_once './includes/authentication.php';
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
            Password Change - GNDPC
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="./assets/css/material-icons.css" />
        <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link href="./assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet" />
        <link rel="stylesheet" href="./assets/main_styles.css">
        <link rel="stylesheet" href="./assets/css/bootstrap-select.min.css">

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
                                <div class="row align-items-center">
                                    <div class="col-md-6 mx-auto ">
                                        <div class="card ">
                                            <div class="card-header card-header-primary">
                                                <h4 class="card-title">Change Password</h4>
                                            </div>
                                            <div class="card-body">
                                                <form id="passwordChangeForm">
                                                    <div class="row justify-content-center mar-row">

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating" for="currentPassword">Current Password</label>
                                                                <input type="password" class="form-control" id="currentPassword" name="currentPassword">
                                                            </div>
                                                        </div>
                                                        <div class="current-password-eye" style="padding: 5px;">
                                                            <i onclick="showPassword('currentPassword',this)" class="material-icons show-eye">remove_red_eye</i>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating" for="newPassword">Enter new password</label>
                                                                <input type="password" class="form-control password" id="newPassword" name="newPassword">
                                                            </div>
                                                        </div>
                                                        <div class="new-password-eye" style="padding: 5px;">
                                                            <i onclick="showPassword('newPassword',this)" class="material-icons show-eye">remove_red_eye</i>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="bmd-label-floating" for="confirmNewPassword">Retype new password</label>
                                                                <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword">
                                                            </div>
                                                        </div>
                                                        <div class="retype-password-eye" style="padding: 5px;">
                                                            <i onclick="showPassword('confirmNewPassword',this)" class="material-icons show-eye">remove_red_eye</i>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary changePasswordButton">Change password</button>
                                                        <div class="clearfix"></div>
                                                    </div>
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
        <script src="./assets/js/core/jquery.min.js"></script>
        <script src="./assets/js/core/popper.min.js"></script>
        <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
        <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->

        <!-- Forms Validations Plugin -->
        <script src="./assets/js/plugins/jquery.validate.min.js"></script>

        <script src="./assets/js/plugins/bootstrap-notify.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="./assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
        <script>
            function showPassword(id, eye) {
                var x = document.getElementById(id);
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
                $(eye).toggleClass("highlight-eye");
            }
        </script>

        <script type="text/javascript">
            $("#passwordChangeForm").on("submit", function(e) {
                e['originalEvent'].preventDefault();
                passwordChange();
            })

            function passwordChange() {
                let data = new FormData(document.getElementById('passwordChangeForm'));
                data.append('passwordChangeSubmit', true);
                $.ajax({
                    type: "POST",
                    url: "data-manipulation.php",
                    data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response == "true") {
                            md.showNotification('top', 'center', 'Password Changed!');
                        } else {
                            md.showNotification('top', 'center', 'Password Not Changed! Please Try Again!');
                        }
                    }
                });
            }
        </script>

        <script src="assets/sidebarSript.js"></script>
    </body>

    </html>