<?php
session_start();
if( !isset($_SESSION['AbSent']) ) 
{
    header("Location: ../index.php");
    exit;
}
$u = explode(":", $_SESSION['AbSent']);
$username = $u[0];
?>