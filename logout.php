<?php
session_start();

if(isset($_POST['logout']) && $_POST['logout'] == "Logout") {
  session_unset();
  session_destroy();
  header("Location: login.php");
  exit();
}
?>