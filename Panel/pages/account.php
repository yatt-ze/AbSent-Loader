<?php
include '../include/session.php';
include '../include/geo.php';
include '../include/stats.php';
$userperms = $odb->query("SELECT permissions FROM users WHERE username = '".$username."'")->fetchColumn(0);

$details = $odb->prepare("SELECT * FROM users WHERE username = :id");
$details->execute(array(":id" => $username));
$details = $details->fetch(PDO::FETCH_ASSOC);

?>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AbSent Dash</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,300,100,700,900' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- inject:css -->
    <link rel="stylesheet" href="../css/lib/getmdl-select.min.css">
    <link rel="stylesheet" href="../css/lib/nv.d3.css">
    <link rel="stylesheet" href="../css/application.css">
    <!-- endinject -->
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <div class="mdl-layout-spacer"></div>

            <div class="avatar-dropdown" id="icon">
            <span><?php echo $username; ?></span>
                <img src="../images/avatar.png">
            </div>

            <!-- Account dropdawn-->
            <ul class="mdl-menu mdl-list mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect mdl-shadow--2dp account-dropdown"
                for="icon">
                <li class="mdl-list__item mdl-list__item--two-line">
                    <span class="mdl-list__item-primary-content">
                    <span><?php echo $username; ?></span>
                    <span class="mdl-list__item-sub-title"><?php echo $userperms; ?></span>
                    </span>
                </li>
                <li class="list__item--border-top"></li>
                <li class="mdl-menu__item mdl-list__item">
                    <a href="account.php" class="mdl-list__item-primary-content">
                        <i class="material-icons mdl-list__item-icon">account_circle</i>
                        My account
                    </a>
                </li>
                <li class="mdl-menu__item mdl-list__item">
                    <a href="settings.php" class="mdl-list__item-primary-content">
                        <i class="material-icons mdl-list__item-icon">settings</i>
                       Settings
                    </a>
                </li>
                <li class="mdl-menu__item mdl-list__item">
                    <a href="../include/logout.php?logout=1" class="mdl-list__item-primary-content">
                        <i class="material-icons mdl-list__item-icon">exit_to_app</i>
                        Logout
                    </a>
                </li>
                
        </div>
    </header>

    <div class="mdl-layout__drawer">
        <header>AbSent</header>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="index.php">
                <i class="material-icons" role="presentation">dashboard</i>
                Dashboard
            </a>
            <a class="mdl-navigation__link mdl-navigation__link" href="clients.php">
                <i class="material-icons" role="presentation">person</i>
                Clients
            </a>
            <a class="mdl-navigation__link" href="tasks.php">
                <i class="material-icons" role="presentation">map</i>
                Tasks
            </a>
        </nav>
    </div>
    <main class="mdl-layout__content">
        <div class="mdl-grid">
        <?php
        if(isset($_POST['changePass']))
        {
            $oldPass = $_POST['oldPass'];
            $oldPassAgain = $_POST['oldPassAgain'];
            $newPass = $_POST['newPass'];
            if(empty($oldPass) || empty($oldPassAgain) || empty($newPass))
            {
                echo '
                <div class="mdl-cell mdl-cell--6-col mdl-cell--3-offset">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Error</h2>
                        </div>
                        <div class="mdl-card__supporting-text no-padding">
                            <h4>One of the feilds are empty</h4>
                        </div>
                    </div>
                </div>         
                ';
            }
            if($oldPass == $oldPassAgain)
            {
                $oh = hash("sha256", $oldPass);
                $op_sql = $odb->prepare("SELECT password FROM users WHERE username = :u");
                $op_sql->execute(array(":u" => $username));
                $op = $op_sql->fetchColumn(0);
                if ($oh == $op)
                {
                    $nh = hash("sha256", $newPass);
                    $up = $odb->prepare("UPDATE users SET password = :p WHERE username = :u");
                    $up->execute(array(":p" => $nh, ":u" => $username));
                    echo '
                    <div class="mdl-cell mdl-cell--6-col mdl-cell--3-offset">
                        <div class="mdl-card mdl-shadow--2dp">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Success</h2>
                            </div>
                            <div class="mdl-card__supporting-text no-padding">
                                <h4>Password Updated</h4>
                            </div>
                        </div>
                    </div>         
                    ';
                }
                else
                {
                    echo '
                    <div class="mdl-cell mdl-cell--6-col mdl-cell--3-offset">
                        <div class="mdl-card mdl-shadow--2dp">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Error</h2>
                            </div>
                            <div class="mdl-card__supporting-text no-padding">
                                <h4>Incorrect Current Password</h4>
                            </div>
                        </div>
                    </div>         
                    ';                    
                }
            }
            else
            {
                echo '
                <div class="mdl-cell mdl-cell--6-col mdl-cell--3-offset">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Error</h2>
                        </div>
                        <div class="mdl-card__supporting-text no-padding">
                            <h4>Passwords Did Not Match</h4>
                        </div>
                    </div>
                </div>         
                ';
            }
        }
        ?>
        <div class="mdl-cell mdl-cell--6-col mdl-cell--3-offset">
            <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Account</h2>
                </div>
                <div class="mdl-card__supporting-text no-padding">  
                    <div align="center"> 
                        <h6>Username: <?php echo ucfirst($username) ?></h6>
                        <h6>Permissions: <?php echo $userperms; ?></h6>
                    </div>
                    <hr>
                    <div align="center">
                        <h4>Change Password</h4>
                        <form method="POST">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                    <input class="mdl-textfield__input" type="password" name="oldPass">
                                    <label class="mdl-textfield__label" for="oldPass">Old Password</label>
                                </div>
                                &nbsp&nbsp
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                    <input class="mdl-textfield__input" type="password" name="oldPassAgain">
                                    <label class="mdl-textfield__label" for="oldPassAgain">Old Password Again</label>
                                </div>
                                &nbsp&nbsp
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                    <input class="mdl-textfield__input" type="password" name="newPass">
                                    <label class="mdl-textfield__label" for="newPass">New Password</label>
                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone submit-cell" align="right">
                            <button type="submit" name="changePass" class="mdl-button mdl-js-button mdl-button--raised color--light-blue">
                                        CHANGE PASSWORD
                            </button>
                        </form>
                    </div>
                </div>  
            </div>       
        </div>
    </main>
</div>

<!-- inject:js -->
<script src="../js/d3.js"></script>
<script src="../js/getmdl-select.min.js"></script>
<script src="../js/material.js"></script>
<script src="../js/nv.d3.js"></script>
<!-- endinject -->

</body>
</html>
