<?php
 session_start();
 if (!isset($_SESSION['AbSent'])) {
  header("Location: ../index.php");
 } else if(isset($_SESSION['AbSent'])!="") {
  header("Location: ../pages/index.php");
 }
 
 if (isset($_GET['logout'])) {
  unset($_SESSION['user']);
  session_unset();
  session_destroy();
  header("Location: ../index.php");
  exit;
 }