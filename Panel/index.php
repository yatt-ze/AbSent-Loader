<?php
    session_start();
    include './include/config.php';
    if ( isset($_SESSION['AbSent'])!="" ) 
    {
        header("Location: pages/index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/DB_16х16.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AbSent Login</title>

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,300,100,700,900' rel='stylesheet'
          type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- inject:css -->
    <link rel="stylesheet" href="css/lib/getmdl-select.css">
    <link rel="stylesheet" href="css/lib/nv.d3.css">
    <link rel="stylesheet" href="css/application.css">
    <!-- endinject -->

</head>
<body >

<div class="mdl-layout mdl-js-layout color--gray is-small-screen login">
    <main class="mdl-layout__content">
    
    <?php
    if (isset($_POST['doLogin']))
    {
        $username = $_POST['username'];
        $cpass = $_POST['password'];
        $password = hash("sha256", $_POST['password']);
        echo $password;
        if (ctype_alnum($username))
        {
            $sel = $odb->prepare("SELECT id,password,status FROM users WHERE username = :user");
            $sel->execute(array(":user" => $username));
            list($userid,$pass,$status) = $sel->fetch();
            if ($pass != "" || $pass != NULL)
            {
                if ($password == $pass)
                {
                    if ($status != 1)
                    {
                        echo "Banned Account";
                    }
                    else
                    {
                        $_SESSION['AbSent'] = $username.":".$userid;;
                        header("location: ./pages/index.php");
                    }
                } 
                else
                {
                    echo "Incorrect Username or Password";
                }
            } 
            else
            { 
                echo "Incorrect Username or Password";
            }
        }
    }
    ?>

        <div class="mdl-cell mdl-cell--3-col mdl-cell--4-offset">
            <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Login</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="">
                    <center>
                    <form class="login-form" action="" method="POST">
                        <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                    <input class="mdl-textfield__input" type="text" name="username">
                                    <label class="mdl-textfield__label" for="e-mail">Username</label>
                                </div>
                                <br>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                    <input class="mdl-textfield__input" type="password" name="password">
                                    <label class="mdl-textfield__label" for="password">Password</label>
                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone submit-cell" align="right">
                            <button type="submit" name="doLogin" class="mdl-button mdl-js-button mdl-button--raised color--light-blue">
                                        SIGN IN
                            </button>
                            </form>
                            </div>
                        </div>
                    </center>
                </div>
            </div>
        </div>

    </main>
</div>

<!-- inject:js -->
<script src="js/d3.js"></script>
<script src="js/getmdl-select.js"></script>
<script src="js/material.js"></script>
<script src="js/nv.d3.js"></script>
<script src="js/layout/layout.js"></script>
<script src="js/scroll/scroll.js"></script>
<script src="js/widgets/charts/discreteBarChart.js"></script>
<script src="js/widgets/charts/linePlusBarChart.js"></script>
<script src="js/widgets/charts/stackedBarChart.js"></script>
<script src="js/widgets/employer-form/employer-form.js"></script>
<script src="js/widgets/line-chart/line-charts-nvd3.js"></script>
<script src="js/widgets/map/maps.js"></script>
<script src="js/widgets/pie-chart/pie-charts-nvd3.js"></script>
<script src="js/widgets/table/table.js"></script>
<script src="js/widgets/todo/todo.js"></script>
<!-- endinject -->

</body>
</html>