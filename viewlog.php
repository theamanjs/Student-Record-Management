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
    Logs - GNDPC
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
              <h4 class="card-title">Logs</h4>
              <!--<p class="card-category">Select the teacher.</p>-->
            </div>
            <div class="card-body">
           
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead class="text-primary">
                                <tr>
                                    <th>Sno</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Teacher Name</th>
                                    <th>Information</th>
                                    <!--<th>Department</th>-->
                                </tr>
                            </thead>
                            <tbody class='log-container'>
                            
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
  function fetchLogs() {
    $.ajax({
        type: "POST",
        url: "data-manipulation.php",
        data: {
            fetchlogs: true,
            department: "<?php echo $teacherData['department']; ?>"
        },
        success: function (response) {
            let data = JSON.parse(response);
            let logtable = '';
            let arrLength=parseInt(data.length);
            for( i = 1; i <= arrLength-1; i++) 
            {
                logtable += `<tr>
                            <th>${i}</th>`;
                logtable += `<td>${data[i][1]}</td>
                            <td>${data[i][2]}</td>
                            <td>${data[i][3]}</td>
                            <td>${data[i][4]}</td>`;
                logtable += `<tr>`;
            }
            $(".log-container").html(logtable);
        }
    });
  }

  fetchLogs();
  </script>
</body>

</html>
