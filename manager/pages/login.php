<?php
session_start();
if(isset($_SESSION['fm_active'])) {
    header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Login - File Manager</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/material-icons.css">
    <style>
        body {
            color: #333;
            background-color: #f5f5f5;
            align-items:center;
            height:100vh;
        }
        .alert-message {
            padding: 0px 8px 16px 8px;
            font-size: 13px;
            color: #777;
        }
        .eye-icon {
            position: absolute;
            right: 5px;
            bottom: 10px;
            cursor: pointer;
            font-size: 22px;
        }
        .eye-icon:hover {
            transition: color .2s;
            color: var(--primary-color-hover);
        }
    </style>
</head>
<body class="flex justify-center">
    <div class="card w-md-4">
        <div class="card-header">
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-control">
                    <label for="username">Username</label>
                    <div class="textbox-container">
                        <input type="text" class="textbox" required name="username" id="username" placeholder="Your Username" autofocus>
                        <span class="textbox-focused"></span>
                    </div>
                </div>
                <div class="form-control">
                    <label for="password">Password</label>
                    <div class="textbox-container">
                        <i class='material-icons eye-icon' onclick="togglePassword()">visibility_off</i>
                        <input type="password" class="textbox" required name="password" id="password" placeholder="Your Password">
                        <span class="textbox-focused"></span>
                    </div>
                </div>
                <?php
                if(isset($_SESSION['wrong_pass'])) {
                    echo '<div class="alert-message">Wrong Password!</div>';
                }
                ?>
                <div class="form-control flex justify-center">
                    <button class="btn btn-primary btn-lg" name="login"> Login </button>
                </div>
            </form>
        </div>
    </div>
<script>
let passwordShown = false;
function togglePassword() {
    if(!passwordShown) {
        document.querySelector('.eye-icon').innerHTML = 'visibility';
        document.getElementById("password").setAttribute('type', 'text');
        passwordShown = true;
    } else {
        document.querySelector('.eye-icon').innerHTML = 'visibility_off';
        document.getElementById("password").setAttribute('type', 'password');
        passwordShown = false;
    }
}
</script>
</body>
</html>
<?php
if(isset($_POST['login'])) {
    if($_POST['username'] == 'satnam' && $_POST['password'] == 'this.Unlock()') {
        $_SESSION['fm_active'] = true;
        $_SESSION['wrong_pass'] = null;
        header('Location: ../index.php');
    } else {
        $_SESSION['wrong_pass'] = true;
        header('Location: login.php');
    }
}
?>