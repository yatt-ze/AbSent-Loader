<?php
include 'config.php';
$knock = $odb->query("SELECT knock FROM settings LIMIT 1")->fetchColumn(0) * 60;
$deadi = $odb->query("SELECT dead FROM settings LIMIT 1")->fetchColumn(0) * 86400;
$o_sql = $odb->prepare("SELECT COUNT(*) FROM clients WHERE lastKnock + :on > UNIX_TIMESTAMP()");
$o_sql->execute(array(":on" => $knock + 120));
$d_sql = $odb->prepare("SELECT COUNT(*) FROM clients WHERE lastKnock + :d < UNIX_TIMESTAMP()");
$d_sql->execute(array(":d" => $deadi));
$online = number_format($o_sql->fetchColumn(0), 0, '.', '');
$dead = number_format($d_sql->fetchColumn(0), 0, '.', '');
$total = number_format($odb->query("SELECT COUNT(*) FROM clients")->fetchColumn(0), 0, '.', '');
$offline = $total - $online - $dead;
?>