<style type="text/css">
    * {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: -moz-none;
        -o-user-select: none;
        user-select: none;
    }
</style>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form" action="student-search.php" method="POST">
                <div class="input-group no-border">
                    <input name="searched-roll" type="text" value="" class="form-control" placeholder="Search Roll">
                    <button name="find-roll" type="submit" class="btn btn-white btn-round btn-just-icon">
                        <i class="material-icons">search</i>
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="changeTheme" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">palette</i>
                  <p class="d-lg-none d-md-block">
                    Theme
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="changeTheme" style="min-width: 200px;">
                  <div class="nav-item d-flex justify-content-center">
                  <div class="fixed-plugin" style="all:unset;">
                    <div href="javascript:void(0)" class="switch-trigger active-color" style="max-height: 23px;">
                        <span class="badge badge-purple" onclick="changeTheme('0')"></span>
                        <span class="badge badge-azure" onclick="changeTheme('1')"></span>
                        <span class="badge badge-green" onclick="changeTheme('2')"></span>
                        <span class="badge badge-warning" onclick="changeTheme('3')"></span>
                        <span class="badge badge-danger" onclick="changeTheme('4')"></span>
                        <span class="badge badge-rose" onclick="changeTheme('5')"></span>
                        <span class="badge badge-purple-corallite" onclick="changeTheme('6')"></span>
                    </div>
                  </div>
                  </div>
                </div>
              </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="material-icons">person</i>
                        <p class="d-lg-none d-md-block">
                            Account
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item" href="./password.php">Password</a>
                        <a class="dropdown-item" href="./logout.php">Log out</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->